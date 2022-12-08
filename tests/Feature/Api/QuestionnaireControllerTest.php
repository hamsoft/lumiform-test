<?php

namespace Tests\Feature\Api;

use App\Models\Answer;
use App\Models\Form;
use App\Models\Question;
use App\Models\Response;
use App\Models\ResponseSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class QuestionnaireControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ITEM_TYPE = 'type';
    private const ELEMENT_DATA = 'data';
    private const SELECTED_RESPONSES = 'selected_responses';
    private const RESPONSE_UUIDS = 'response_uuids';

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
            'selected answer' => [
                'items' => [
                    0 => [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [],
                    ],
                ],
                'answers' => [
                    0 => [
                        'responses' => [],
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

            'without responses' => [
                'items' => [
                    [
                        self::ITEM_TYPE => Question::MODEL_TYPE,
                        static::ELEMENT_DATA => [],
                        self::SELECTED_RESPONSES => [],
                    ],
                ],
                'answers' => [
                    [
                        Answer::QUESTION_UUID => null
                    ],
                ],
                'expectedFragments' => [
                    ["answers.0.response_uuids" => ["The responses field is required."]],
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
     * @param \App\Models\Form $form
     *
     * @return array
     */
    protected function prepareFormItemsAndAnswers($formItemsElementsData, array $answers, Form $form): array
    {
        foreach ($formItemsElementsData as $key => $element) {

            $questionFactory = Question::factory();

            $questionFactory->state([
                Question::NOTES_ALLOWED => false,
                Question::PHOTOS_ALLOWED => false,
                Question::ISSUES_ALLOWED => false,
                Question::NEGATIVE => false,
                Question::RESPONDED => false,
                Question::REQUIRED => false,
            ]);

            $questionFactory->for(ResponseSet::factory()->has(Response::factory()));

            /** @var Question $question */
            $question = $questionFactory->create($element[self::ELEMENT_DATA]);

            $item = $form->makeItem();
            $item->setElement($question);
            $item->save();

            if (isset($answers[$key])) {
                $answers[$key] = $this->prepareAnswer($question, $element, $answers[$key]);
            }
        }

        return $answers;
    }

    /**
     * @param \App\Models\Question $question
     * @param $element
     *
     * @param array $answer
     *
     * @return array
     */
    protected function prepareAnswer(Question $question, $element, array &$answer): array
    {
        $answer[Answer::QUESTION_UUID] = $question->uuid;

        if ($element[self::ITEM_TYPE] !== Question::MODEL_TYPE) {
            return $answer;
        }


        $selectResponses = $element[self::SELECTED_RESPONSES] ?? [0];
        $responses = $question->responseSet ? $question->responseSet->responses : [];

        $answer[self::RESPONSE_UUIDS] = [];
        foreach ($selectResponses as $responseKey) {
            $responseUuid = $responses[$responseKey] ?? fake()->uuid();
            $answer[self::RESPONSE_UUIDS][] = $responseUuid;
        }

        return $answer;
    }
}
