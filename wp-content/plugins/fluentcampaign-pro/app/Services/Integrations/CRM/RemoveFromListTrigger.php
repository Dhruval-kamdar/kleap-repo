<?php

namespace FluentCampaign\App\Services\Integrations\CRM;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;

class RemoveFromListTrigger extends BaseTrigger
{
	public function __construct()
	{
		$this->triggerName = 'fluentcrm_contact_removed_from_lists';
		$this->actionArgNum = 2;
		$this->priority = 20;

		parent::__construct();
	}

	public function getTrigger()
	{

		return [
			'category'    => 'CRM',
			'label'       => 'List Removed',
			'description' => 'This will run when selected lists will be removed from a contact'
		];
	}

	public function getFunnelSettingsDefaults()
	{
		return [
			'lists'       => [],
			'select_type' => 'any'
		];
	}

	public function getSettingsFields($funnel)
	{
		return [
			'title'     => 'Remove From List',
			'sub_title' => 'This will run when any of the selected lists will be removed from a contact',
			'fields'    => [
				'lists' => [
					'type'        => 'option_selectors',
					'option_key'  => 'lists',
					'is_multiple' => true,
					'label'       => 'Select Lists',
					'placeholder' => 'Select List'
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
		$listsTobeRemoved = $originalArgs[0];
		$subscriber = $originalArgs[1];

		$willProcess = $this->isProcessable($funnel, $listsTobeRemoved, $subscriber);
		$willProcess = apply_filters('fluentcrm_funnel_will_process_' . $this->triggerName, $willProcess, $funnel, $subscriber, $originalArgs);

		if (!$willProcess) {
			return;
		}

		(new FunnelProcessor())->startFunnelSequence($funnel, [], [
			'source_trigger_name' => $this->triggerName
		], $subscriber);
	}

	private function isProcessable($funnel, $listsTobeRemoved, $subscriber)
	{
		$lists = $funnel->settings['lists'];

		// Intersection of funnel settings lists & lists
		// to be removed will get the matching list ids.
		$intersection = array_intersect($lists, $listsTobeRemoved);

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
