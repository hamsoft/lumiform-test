<?php

namespace Tests\Feature\Api;

use App\Models\Form;
use App\Models\Form\FormItem;
use App\Models\Model;
use App\Models\Page;
use App\Models\Question;
use App\Models\ResponseSet;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FormControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider provideCreateNewFormSuccessfullyData
     *
     * @param array $requestData
     * @param array $expectedFragments
     *
     * @return void
     */
    public function testCreateNewFormSuccessfully(array $requestData, array $expectedFragments): void
    {
        $response = $this->postJson('/api/form', $requestData);

        $response->assertCreated();

        foreach ($expectedFragments as $expectedFragment) {
            $response->assertJsonFragment($expectedFragment);
        }

        $response->assertJsonStructure(['uuid', 'message',]);
        $this->assertNotEmpty($response['uuid']);

        $formData = $requestData;
        $formData[Model::UUID] = $response['uuid'];
        $items = $formData['items'] ?? [];
        unset($formData['items']);

        $form = Form::findOrFail($formData[Model::UUID]);

        $this->assertModelExists($form);

        if (empty($items)) {
            return;
        }

        $form->load('itemsWithElements');

        foreach ($items as $item) {
            $element = $form->itemsWithElements
                ->where(FormItem::ELEMENT_TYPE, $item['type'])
                ->where(FormItem::RELATION_ELEMENT . '.title', $item['title'])
                ->first();

            $this->assertModelExists($element);
        }
    }

    /**
     * @return array[]
     */
    public function provideCreateNewFormSuccessfullyData(): array
    {
        $this->refreshApplication();
        $pageFactory = Page::factory();
        $sectionFactory = Section::factory();
        $questionFactory = Question::factory();

        return [
            'form without items' => [
                'requestData' => $this->prepareFormRequest(),
                'expectedFragments' => [['message' => 'Successfully Created']],
            ],
            'with items' => [
                'requestData' => $this->prepareFormRequest([
                    'items' => [
                        $pageFactory->make(['type' => Page::MODEL_TYPE])->toArray(),
                        $sectionFactory->make([
                            'type' => 'section',
                            'items' => [
                                $sectionFactory->make([
                                    'type' => 'section'
                                ])->toArray()
                            ]
                        ])->toArray(),
                        $questionFactory
                            ->for(ResponseSet::factory())
                            ->make([
                                'type' => Page::MODEL_TYPE,
                            ])
                            ->toArray(),
                    ],
                ]),
                'expectedFragments' => [['message' => 'Successfully Created']],
            ],
        ];
    }

    /**
     * @dataProvider provideCreateNewFormFailData
     *
     * @param array $requestData
     * @param array $expectedFragments
     *
     * @return void
     */
    public function testCreateNewFormFail(array $requestData, array $expectedFragments): void
    {
        $response = $this->postJson('/api/form', $requestData);

        $response->assertUnprocessable();
        foreach ($expectedFragments as $expectedFragment) {
            $response->assertJsonFragment($expectedFragment);
        }
    }

    /**
     * @return array[]
     */
    public function provideCreateNewFormFailData(): array
    {
        $this->refreshApplication();
        $pageFactory = Page::factory();
        $sectionFactory = Section::factory();
        $questionFactory = Question::factory();

        return [
            'without title' => [
                'requestData' => $this->prepareFormRequest([Form::TITLE => null]),
                'expectedFragments' => [['The title field is required.']],
            ],
            'without description' => [
                'requestData' => $this->prepareFormRequest([Form::DESCRIPTION => null]),
                'expectedFragments' => [['The description field is required.']],
            ],
            'fail when page without title' => [
                'requestData' => $this->prepareFormRequest([
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
                                    ],
                                ]),
                            ],
                        ])->toArray(),
                    ],
                ]),
                'expectedFragments' => [
                    ['checklist.form.items.0.type' => ['The type field is required.']],
                    ['checklist.form.items.1.type' => ['The selected type is invalid.']],
                    ['checklist.form.items.2.title' => ['The title field is required.']],
                    ['checklist.form.items.3.title' => ['The title field is required.']],
                    ['checklist.form.items.4.items.0.items.1.title' => ['The title field is required.']],
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
        $formQuestion = Question::factory()
            ->for(ResponseSet::factory()->create())
            ->create();

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
                            'uuid' => $page->uuid,
                            'type' => Page::MODEL_TYPE,
                            'title' => $page->title,
                            'items' => $this->mapQuestions($pageQuestions),
                        ],
                        [
                            'uuid' => $section->uuid,
                            'type' => Section::MODEL_TYPE,
                            'title' => $section->title,
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
     * @param \Illuminate\Support\Collection<Model> $elements
     * @param \App\Models\Model|null $parent
     *
     * @return void
     */
    private function prepareFormItems(Collection $formItems, iterable $elements, ?Model $parent = null): void
    {
        foreach ($elements as $element) {
            $formItem = FormItem::make();
            $formItem->element()->associate($element);
            if ($parent) {
                $formItem->parent()->associate($parent);
            }

            $formItems->push($formItem->toArray());
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
            'uuid' => $formQuestion->uuid,
            'type' => Question::MODEL_TYPE,
            'title' => $formQuestion->title,
            'image_id' => $formQuestion->image_id,
            'negative' => $formQuestion->negative,
            'notes_allowed' => $formQuestion->notes_allowed,
            'photos_allowed' => $formQuestion->photos_allowed,
            'issues_allowed' => $formQuestion->issues_allowed,
            'responded' => $formQuestion->responded,
            'required' => $formQuestion->required,
            'response_type' => $formQuestion->response_type,
            'params' => [
                'response_set' => $formQuestion->response_set_uuid,
                'multiple_selection' => $formQuestion->multiple_selection,
            ],
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

    /**
     * @param array $formData
     *
     * @return mixed
     */
    protected function prepareFormRequest(array $formData = []): array
    {
        return Form::factory()->count(1)->make($formData)->map(fn($form) => [
            'checklist' => [
                'checklist_title' => $form->title,
                'checklist_description' => $form->description,
                'form' => [
                    'items' => $form->items ?? [],
                ]
            ],
        ])->first();
    }
}
