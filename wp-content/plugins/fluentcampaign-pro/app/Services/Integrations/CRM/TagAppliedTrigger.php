<?php

namespace FluentCampaign\App\Services\Integrations\CRM;

use FluentCrm\App\Services\Funnel\BaseTrigger;
use FluentCrm\App\Services\Funnel\FunnelHelper;
use FluentCrm\App\Services\Funnel\FunnelProcessor;

class TagAppliedTrigger extends BaseTrigger
{
	public function __construct()
	{
		$this->triggerName = 'fluentcrm_contact_added_to_tags';
		$this->actionArgNum = 2;
		$this->priority = 20;

		parent::__construct();
	}

	public function getTrigger()
	{
		return [
			'category'    => 'CRM',
			'label'       => 'Tag Applied',
			'description' => 'This will run when selected tags will be applied to a contact'
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
			'title'     => 'Tag Applied',
			'sub_title' => 'This will run when selected tags will be applied to a contact',
			'fields'    => [
				'tags'        => [
					'type'        => 'option_selectors',
					'option_key'  => 'tags',
					'is_multiple' => true,
					'label'       => 'Select Tags',
					'placeholder' => 'Select Tag'
				],
				'select_type' => [
					'label'      => 'Run When',
					'type'       => 'radio',
					'options'    => [
						[
							'id'    => 'any',
							'title' => 'contact added in any of the selected tags'
						],
						[
							'id'    => 'all',
							'title' => 'contact added in all of the selected tags'
						]
					],
					'dependency' => [
						'depends_on' => 'tags',
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
		$tags = $funnel->settings['tags'];
		$selectType = $funnel->settings['select_type'];

		$subscriberTags = $subscriber->tags->pluck('id');

		// Intersection of funnel tags & subscriber
		// tags will get the matching tag ids.
		$intersection = array_intersect($tags, $subscriberTags);

		if ($selectType === 'any') {
			// At least one funnel tag id is available.
			$match = !empty($intersection);
		} else {
			// All of the funnel tag ids are present.
			$match = count($intersection) === count($tags);
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
