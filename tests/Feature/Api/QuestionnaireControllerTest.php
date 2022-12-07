<?php

namespace Tests\Feature\Api;

use App\Models\Answer;
use App\Models\Form;
use App\Models\Form\FormItem;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class QuestionnaireControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ITEM_TYPE = 'type';
    private const ELEMENT_DATA = 'data';

    /**
     * @dataProvider provideSaveAnswersSuccessfullyData
     *
     * @param array $formItemsElementsData
     * @param array $answers
     *
     * @return void
     */
    public function testSaveAnswersSuccessfully(array $formItemsElementsData, array $answers): void
    {
        /** @var Form $form */
        $form = Form::factory()->create();

        $answers = $this->prepareFormItemsAndAnswers($formItemsElementsData, $answers, $form);

        $response = $this->postJson('api/questionnaire', [
            'uuid' => $form->uuid,
            'answers' => $answers,
        ]);

        $response->assertCreated();

        $this->assertEquals(count($answers), Answer::count());
    }

    public function provideSaveAnswersSuccessfullyData(): array
    {
        $this->refreshApplication();

        return [
            'without answers' => [
                'items' => [
                    0 => [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [],
                    ],
                ],
                'answers' => [
                    0 => [
                        'response' => 'blaa'
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideSaveAnswersFailsData
     *
     * @param array $formItemsElementsData
     * @param array $answers
     * @param array $expectedFragments
     * @param array $expectedStructure
     * @param string|null $formUuid
     *
     * @return void
     */
    public function testSaveAnswersFails(
        array $formItemsElementsData,
        array $answers,
        array $expectedFragments,
        array $expectedStructure = [],
        ?string $formUuid = null
    ): void {
        if (!empty($formItemsElementsData)) {
            /** @var Form $form */
            $form = Form::factory()->create();

            $answers = $this->prepareFormItemsAndAnswers($formItemsElementsData, $answers, $form);

            $requestData = [
                'uuid' => $form->uuid ?? $formUuid,
                'answers' => $answers,
            ];
        }

        $response = $this->postJson('api/questionnaire', $requestData ?? []);

        $response->assertUnprocessable();

        foreach ($expectedFragments as $expectedFragment) {
            if (is_array($expectedFragment)) {
                $response->assertJsonFragment($expectedFragment);
            }
        }

        $response->assertJsonStructure($expectedStructure);
    }

    public function provideSaveAnswersFailsData(): array
    {
        return [
            'without form' => [
                'items' => [],
                'answers' => [],
                'expectedFragments' => [[
                    "answers" => ["The answers field is required."],
                    "uuid" => ["The uuid field is required."],
                ]],
            ],

            'wrong form uuid' => [
                'items' => [],
                'answers' => [],
                'expectedFragments' => [[
                    "uuid" => ["The uuid field is required."],
                    "answers" => ["The answers field is required."],
                ]],
                'expectedStructure' => [],
                'formUuid' => 'not-exists',
            ],

            'without answers' => [
                'items' => [
                    [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [],
                    ],
                ],
                'answers' => [
                    []
                ],
                'expectedFragments' => [
                    ["answers.0.question_uuid" => ["The question_uuid field is required."]],
                ],
            ],

            'notes prohibited' => [
                'items' => [
                    [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [
                            Question::NOTES_ALLOWED => false,
                        ],
                    ],
                ],
                'answers' => [
                    [
                        'notes' => fake()->text(),
                    ],
                ],
                'expectedFragments' => [
                    ["answers.0.notes" => ["The answers.0.notes field is prohibited."]],
                ],
            ],

            'issues prohibited' => [
                'items' => [
                    [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [
                            Question::ISSUES_ALLOWED => false,
                        ],
                    ],
                ],
                'answers' => [
                    [
                        'issues' => [],
                    ],
                ],
                'expectedFragments' => [
                    ["answers.0.issues" => ["The answers.0.issues field is prohibited."]],
                ],
            ],

            'photos prohibited' => [
                'items' => [
                    [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [
                            Question::PHOTOS_ALLOWED => false,
                        ],
                    ],
                ],
                'answers' => [
                    [
                        'photos' => UploadedFile::fake()->image('avatar.jpg'),
                    ],
                ],
                'expectedFragments' => [
                    ["answers.0.photos" => ["The answers.0.photos field is prohibited."]],
                ],
            ],

            'unaccountable photo type' => [
                'items' => [
                    [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [
                            Question::PHOTOS_ALLOWED => true,
                        ],
                    ],
                ],
                'answers' => [
                    [
                        'photos' => UploadedFile::fake()->image('avatar.pdf'),
                    ],
                ],
                'expectedFragments' => [
                    ["answers.0.photos" => ["The answers.0.photos must be a file of type: jpg, bmp, png."]],
                ],
            ],

            'not answered on whole required questions' => [
                'items' => [
                    0 => [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [
                            Question::REQUIRED => true,
                        ],
                    ],
                    1 => [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [
                            Question::REQUIRED => true,
                        ],
                    ],
                    2 => [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [
                            Question::REQUIRED => true,
                        ],
                    ],
                ],
                'answers' => [
                    1 => [
                        'response' => '32131',
                    ],
                ],
                'expectedFragments' => [
                    [],
                ],
                'expectedStructure' => [
                    'message',
                    'errors' => [
                        'answers.required_questions'
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $formItemsElementsData
     * @param array $answers
     * @param \App\Models\Form\FormItemElement|\App\Models\Form $form
     *
     * @return array
     */
    protected function prepareFormItemsAndAnswers($formItemsElementsData, array $answers, Form\FormItemElement|Form $form): array
    {
        foreach ($formItemsElementsData as $key => $element) {
            /** @var \App\Models\Form\FormItemElement $question */
            $question = Question::factory()->state([
                Question::NOTES_ALLOWED => false,
                Question::PHOTOS_ALLOWED => false,
                Question::ISSUES_ALLOWED => false,
                Question::NEGATIVE => false,
                Question::RESPONDED => false,
                Question::REQUIRED => false,
            ])->create($element[self::ELEMENT_DATA]);

            $form->items()->create([
                FormItem::ELEMENT_TYPE => $question->getElementType(),
                FormItem::ELEMENT_UUID => $question->getUuid(),
            ]);

            if (empty($answers[$key])) {
                continue;
            }

            if ($element[self::ITEM_TYPE] === Question::MODEL_TYPE) {
                $answers[$key][Answer::QUESTION_UUID] = $question->uuid;
            }
        }
        return $answers;
    }

}
