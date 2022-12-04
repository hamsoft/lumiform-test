<?php

namespace Tests\Feature\Api;

use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * @param array $expectedFragment
     *
     * @return void
     */
    public function testCreateNewForm(array $requestData, int $expectedStatus, array $expectedFragment): void
    {
        $response = $this->postJson('/api/form', $requestData);

        $response->assertStatus($expectedStatus);
        $response->assertJsonFragment($expectedFragment);

        if (Response::HTTP_CREATED === $expectedStatus) {
            $response->assertJsonStructure(['uuid', 'message',]);
            $this->assertNotEmpty($response['uuid']);
            $this->assertDatabaseHas(Form::TABLE, $requestData + [Form::UUID => $response['uuid']]);
        }
    }

    /**
     * @return array[]
     */
    public function provideCreateNewFormData(): array
    {
        $this->refreshApplication();
        $formFactory = Form::factory();

        return [
            'form without items' => [
                'requestData' => $formFactory->make()->toArray(),
                'expectedStatus' => Response::HTTP_CREATED,
                'expectedFragment' => ['message' => 'Successfully Created'],
            ],
            'without title' => [
                'requestData' => $formFactory->make([Form::TITLE => null])->toArray(),
                'expectedStatus' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'expectedFragment' => ['The title field is required.'],
            ],
            'without description' => [
                'requestData' => $formFactory->make([Form::DESCRIPTION => null])->toArray(),
                'expectedStatus' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'expectedFragment' => ['The description field is required.'],
            ],
        ];
    }
}
