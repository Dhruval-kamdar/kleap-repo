<?php

namespace FluentCampaign\App\Services\Integrations\CRM;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;

class ListAppliedTrigger extends BaseTrigger
{
	public function __construct()
	{
		$this->triggerName = 'fluentcrm_contact_added_to_lists';
		$this->actionArgNum = 2;
		$this->priority = 20;

		parent::__construct();
	}

	public function getTrigger()
	{
		return [
			'category'    => 'CRM',
			'label'       => 'List Applied',
			'description' => 'This will run when selected lists will be applied to a contact'
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
			'title'     => 'List Applied',
			'sub_title' => 'This will run when selected lists will be applied to a contact',
			'fields'    => [
				'lists'       => [
					'type'        => 'option_selectors',
					'option_key'  => 'lists',
					'is_multiple' => true,
					'label'       => 'Select Lists',
					'placeholder' => 'Select List'
				],
				'select_type' => [
					'label'      => 'Run When',
					'type'       => 'radio',
					'options'    => [
						[
							'id'    => 'any',
							'title' => 'contact added in any of the selected lists'
						],
						[
							'id'    => 'all',
							'title' => 'contact added in all of the selected lists'
						]
					],
					'dependency' => [
						'depends_on' => 'lists',
						'operator'   => '!=',
						'value'      => []
					]
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
		$subscriber = $originalArgs[1];

		$willProcess = $this->isProcessable($funnel, $subscriber);
		$willProcess = apply_filters('fluentcrm_funnel_will_process_' . $this->triggerName, $willProcess, $funnel, $subscriber, $originalArgs);

		if (!$willProcess) {
			return;
		}

		(new FunnelProcessor())->startFunnelSequence($funnel, [], [
			'source_trigger_name' => $this->triggerName
		], $subscriber);
	}

	private function isProcessable($funnel, $subscriber)
	{
		$lists = $funnel->settings['lists'];
		$selectType = $funnel->settings['select_type'];

		$subscriberLists = $subscriber->lists->pluck('id');

		// Intersection of funnel lists & subscriber
		// lists will get the matching list ids.
		$intersection = array_intersect($lists, $subscriberLists);

		if ($selectType === 'any') {
			// At least one funnel list id is available.
			$match = !empty($intersection);
		} else {
			// All of the funnel list ids are present.
			$match = count($intersection) === count($lists);
		}

		if (!$match) {
			return false;
		}

		// check run_only_one
		if ($subscriber && FunnelHelper::ifAlreadyInFunnel($funnel->id, $subscriber->id)) {
			return false;
		}

		return true;
	}
}
