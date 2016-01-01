<?php namespace Anomaly\MultipleFieldType\Command;

use Anomaly\MultipleFieldType\MultipleFieldType;
use Anomaly\MultipleFieldType\Table\LookupTableBuilder;
use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Config\Repository;

/**
 * Class GetLookupDefinition
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\MultipleFieldType\Command
 */
class GetLookupDefinition implements SelfHandling
{

    /**
     * The lookup table.
     *
     * @var LookupTableBuilder
     */
    protected $table;

    /**
     * Create a new HydrateLookupTable instance.
     *
     * @param LookupTableBuilder $table
     */
    public function __construct(LookupTableBuilder $table)
    {
        $this->table = $table;
    }

    /**
     * Handle the command.
     *
     * @param MultipleFieldType $fieldType
     * @param AddonCollection       $addons
     * @param Repository            $config
     * @return array
     */
    public function handle(MultipleFieldType $fieldType, AddonCollection $addons, Repository $config)
    {
        $definition = [];

        /* @var Addon $addon */
        foreach ($addons->withConfig('lookup.' . $this->table->config('related')) as $addon) {
            $definition = $config->get($addon->getNamespace('lookup.' . $this->table->config('related')));
        }

        $definition = $config->get($fieldType->getNamespace('lookup.' . $this->table->config('related')), $definition);

        return $definition;
    }
}