<?php

namespace App\Services\Implementations;

use App\Models\Form\FormItemElement;
use App\Models\Page;
use App\Models\Question;
use App\Models\Section;
use App\Services\Form\FormItemElementService;
use App\Services\Form\FormItemService as FormItemServiceInterface;
use App\Services\PageService;
use App\Services\QuestionService;
use App\Services\SectionService;
use Illuminate\Contracts\Container\Container;
use RuntimeException;

class FormItemServiceImpl implements FormItemServiceInterface
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    private Container $container;

    public function __construct(Container $container)
    {

        $this->container = $container;
    }

    public function getOrCreateFormItemElement(string $type, array $elementData): FormItemElement
    {
        $elementServiceByType = $this->getElementServiceByType($type);

        return $elementServiceByType->findByUuidOrCreate($elementData);
    }

    /**
     * @param string $type
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getElementServiceByType(string $type): FormItemElementService
    {
        $services = [
            Question::MODEL_TYPE => QuestionService::class,
            Page::MODEL_TYPE => PageService::class,
            Section::MODEL_TYPE => SectionService::class,
        ];

        if (isset($services[$type])) {
            return $this->container->make($services[$type] ?? FormItemElementService::class);
        }

        throw new RuntimeException('Element type not defined');
    }
}
