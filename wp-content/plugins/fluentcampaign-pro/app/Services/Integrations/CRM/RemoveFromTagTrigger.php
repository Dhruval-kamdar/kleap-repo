<?php

namespace FluentCampaign\App\Services\Integrations\CRM;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;

class RemoveFromTagTrigger extends BaseTrigger
{
	public function __construct()
	{
		$this->triggerName = 'fluentcrm_contact_removed_from_tags';
		$this->actionArgNum = 2;
		$this->priority = 20;

		parent::__construct();
	}

	public function getTrigger()
	{

		return [
			'category'    => 'CRM',
			'label'       => 'Tag Removed',
			'description' => 'This will run when selected Tags will be removed from a contact'
		];
	}

	public function getFunnelSettingsDefaults()
	{
		return [
			'tags'        => [],
			'select_type' => 'any'
		];
	}

	public function getSettingsFields($funnel)
	{
		return [
			'title'     => 'Remove From Tags',
			'sub_title' => 'This will run when any of the selected tags will be removed from a contact',
			'fields'    => [
				'tags' => [
					'type'        => 'option_selectors',
					'option_key'  => 'tags',
					'is_multiple' => true,
					'label'       => 'Select Tags',
					'placeholder' => 'Select Tag'
				]
			]
		];
	}

	public function getFunnelConditionDefaults($funnel)
	{
		return [];
	}

	public function getConditionFields($funnel)
	{
		return [];
	}

	public function handle($funnel, $originalArgs)
	{
		$tagsTobeRemoved = $originalArgs[0];
		$subscriber = $originalArgs[1];

		$willProcess = $this->isProcessable($funnel, $tagsTobeRemoved, $subscriber);
		$willProcess = apply_filters('fluentcrm_funnel_will_process_' . $this->triggerName, $willProcess, $funnel, $subscriber, $originalArgs);

		if (!$willProcess) {
			return;
		}

		(new FunnelProcessor())->startFunnelSequence($funnel, [], [
			'source_trigger_name' => $this->triggerName
		], $subscriber);
	}

	private function isProcessable($funnel, $tagsTobeRemoved, $subscriber)
	{
		$tags = $funnel->settings['tags'];

		// Intersection of funnel settings tags & tags
		// to be removed will get the matching tag ids.
		$intersection = array_intersect($tags, $tagsTobeRemoved);

		if (empty($intersection)) {
			return false;
		}

		// check run_only_one
		if ($subscriber && FunnelHelper::ifAlreadyInFunnel($funnel->id, $subscriber->id)) {
			return false;
		}

		return true;
	}
}
