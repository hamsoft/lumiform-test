<?php

namespace Tests\Feature\Api;

use App\Models\Form;
use App\Models\Form\FormItem;
use App\Models\Form\FormItemElement;
use App\Models\Page;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FormControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider provideCreateNewFormData
     *
     * @param array $requestData
     * @param int $expectedStatus
     * @param array $expectedFragments
     *
     * @return void
     */
    public function testCreateNewForm(array $requestData, int $expectedStatus, array $expectedFragments): void
    {
        $response = $this->postJson('/api/form', $requestData);

        $response->assertStatus($expectedStatus);
        foreach ($expectedFragments as $expectedFragment) {
            $response->assertJsonFragment($expectedFragment);
        }

        if (Response::HTTP_CREATED !== $expectedStatus) {
            return;
        }

        $response->assertJsonStructure(['uuid', 'message',]);
        $this->assertNotEmpty($response['uuid']);

        $formData = $requestData;
        $formData[Form::UUID] = $response['uuid'];
        unset($formData['items']);
        $this->assertDatabaseHas(Form::TABLE, $formData);
    }

    /**
     * @return array[]
     */
    public function provideCreateNewFormData(): array
    {
        $this->refreshApplication();
        $formFactory = Form::factory();
        $pageFactory = Page::factory();
        $sectionFactory = Section::factory();
        $questionFactory = Question::factory();

        return [
            'form without items' => [
                'requestData' => $formFactory->make()->toArray(),
                'expectedStatus' => Response::HTTP_CREATED,
                'expectedFragments' => [['message' => 'Successfully Created']],
            ],
            'without title' => [
                'requestData' => $formFactory->make([Form::TITLE => null])->toArray(),
                'expectedStatus' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'expectedFragments' => [['The title field is required.']],
            ],
            'without description' => [
                'requestData' => $formFactory->make([Form::DESCRIPTION => null])->toArray(),
                'expectedStatus' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'expectedFragments' => [['The description field is required.']],
            ],
            'with items' => [
                'requestData' => $formFactory->make([
                    'items' => [
                        $pageFactory->make(['type' => Page::MODEL_TYPE])->toArray(),
                        $sectionFactory->make([
                            'type' => 'section',
                            'items' => [
                                $sectionFactory->make([
                                    'type' => 'section'
                                ])->toArray()
                            ]
                        ])->toArray()
                    ]
                ])->toArray(),
                'expectedStatus' => Response::HTTP_CREATED,
                'expectedFragments' => [['message' => 'Successfully Created']],
            ],
            'fail when page without title' => [
                'requestData' => $formFactory->make([
                    'items' => [
                        $pageFactory->make(['type' => null])->toArray(), // Without type
                        $pageFactory->make(['type' => 'error-item'])->toArray(), // Item type not exists
                        $pageFactory->make(['type' => Page::MODEL_TYPE, 'title' => null])->toArray(), // Without page title
                        $sectionFactory->make(['type' => Section::MODEL_TYPE, 'title' => null])->toArray(), // Without section title
                        $pageFactory->make([
                            'type' => Page::MODEL_TYPE,
                            'title' => 'Title',
                            'items' => [
                                $sectionFactory->make([
                                    'type' => Section::MODEL_TYPE,
                                    'items' => [
                                        $questionFactory->make(['type' => Question::MODEL_TYPE,]),
                                        $questionFactory->make([
                                            'type' => Question::MODEL_TYPE,
                                            'title' => null,
                                        ]),
                                        $questionFactory->make(['type' => Question::MODEL_TYPE,]),
                                    ]
                                ]),
                            ],
                        ])->toArray(), // Question in Section without Title
                    ]
                ])->toArray(),
                'expectedStatus' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'expectedFragments' => [
                    ['items.0.type' => ['The type field is required.']],
                    ['items.1.type' => ['The selected type is invalid.']],
                    ['items.2.title' => ['The title field is required.']],
                    ['items.3.title' => ['The title field is required.']],
                    ['items.4.items.0.items.1.title' => ['The title field is required.']],
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    public function testGetFormSuccessfully(): void
    {
        /** @var Form $form */
        $form = Form::factory()->create();

        $formItems = new Collection();

        /** @var Page $page */
        $page = Page::factory()->create();
        $pageQuestions = Question::factory()->count(2)->create();

        $this->prepareFormItems($formItems, [$page]);
        $this->prepareFormItems($formItems, $pageQuestions, $page);

        /** @var Section $section */
        $section = Section::factory()->create();
        $sectionQuestions = Question::factory()->count(1)->create();

        $this->prepareFormItems($formItems, [$section]);
        $this->prepareFormItems($formItems, $sectionQuestions, $section);

        /** @var Question $formQuestion */
        $formQuestion = Question::factory()->create();
        $this->prepareFormItems($formItems, [$formQuestion]);

        $form->items()->createMany($formItems);

        $uuid = $form->uuid;

        $response = $this->getJson('/api/form/' . $uuid);

        $response->assertOk();

        $response->assertJson([
            'checklist' => [
                'checklist_title' => $form->title,
                'checklist_description' => $form->description,
                'form' => [
                    'uuid' => $form->uuid,
                    'type' => Form::MODEL_TYPE,
                    'items' => [
                        [
                            'uuid' => $page->getUuid(),
                            'type' => $page->getElementType(),
                            'title' => $page->getTitle(),
                            'items' => $this->mapQuestions($pageQuestions),
                        ],
                        [
                            'uuid' => $section->getUuid(),
                            'type' => $section->getElementType(),
                            'title' => $section->getTitle(),
                            'repeat' => $section->repeat,
                            'weight' => $section->weight,
                            'required' => $section->required,
                            'items' => $this->mapQuestions($sectionQuestions),
                        ],
                        $this->mapQuestion($formQuestion),
                    ],
                ],
            ]
        ]);
    }

    public function testGetFormNotFound(): void
    {
        $uuid = 'form-not-found-uuid';
        $response = $this->getJson('/api/form/' . $uuid);

        $response->assertNotFound();
    }

    /**
     * @param \Illuminate\Support\Collection $formItems
     * @param \Illuminate\Support\Collection<FormItemElement> $elements
     * @param \App\Models\Form\FormItemElement|null $parent
     *
     * @return void
     */
    private function prepareFormItems(Collection $formItems, iterable $elements, ?FormItemElement $parent = null): void
    {
        foreach ($elements as $element) {
            $formItems->push([
                FormItem::ELEMENT_UUID => $element->getUuid(),
                FormItem::ELEMENT_TYPE => $element->getElementType(),
                FormItem::PARENT_UUID => $parent?->getUuid(),
                FormItem::PARENT_TYPE => $parent?->getElementType(),
            ]);
        }
    }

    /**
     * @param \App\Models\Question $formQuestion
     *
     * @return array
     */
    protected function mapQuestion(Question $formQuestion): array
    {
        return [
            'uuid' => $formQuestion->getUuid(),
            'type' => $formQuestion->getElementType(),
            'title' => $formQuestion->getTitle(),
            'image_id' => $formQuestion->image_id,
            'negative' => $formQuestion->negative,
            'notes_allowed' => $formQuestion->notes_allowed,
            'photos_allowed' => $formQuestion->photos_allowed,
            'issues_allowed' => $formQuestion->issues_allowed,
            'responded' => $formQuestion->responded,
            'required' => $formQuestion->required,
            'response_type' => $formQuestion->response_type,
        ];
    }

    /**
     * @param \Illuminate\Support\Collection $questions
     *
     * @return array
     */
    protected function mapQuestions(Collection $questions): array
    {
        return $questions->map(fn($question) => $this->mapQuestion($question))->toArray();
    }
}
