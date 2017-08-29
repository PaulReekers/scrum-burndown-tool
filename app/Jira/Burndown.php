<?php
namespace App\Jira;

use Exception;

class Burndown
{
    protected $boardId;

    public function __construct($boardId)
    {
        $this->boardId = $boardId;
        $this->apiUrl = env('JIRA_API_URL');
        $this->agileUrl = env('JIRA_AGILE_URL');
        $this->resourcesSearch = env('RESOURCES_SEARCH');
        $this->resourcesFilter = env('RESOURCES_FILTER');
        $this->storypointField = env('STORYPOINT_FIELD');
    }

    public function getFilterTotalCount($id = null)
    {
        $response = $this->getFilterData($id);

        if (!isset($response['jql'])) {
            throw new Exception('Response missing _jql_ field', 1);
        }

        $get = [
            'jql' => $response['jql'],
            'maxResults' => 0
        ];

        $response = $this->getSearchData($get);

        if (!isset($response['total'])) {
            throw new Exception('Response missing _total_ field', 1);
        }

        return $response['total'];
    }

    public function getSprintProgress()
    {
        $activeSprintId = $this->getActiveSprintId();

        $get = [
            'fields' => $this->storypointField . ',' . 'status'
        ];

        $sprintIssues = $this->getSprintIssues($activeSprintId, $get);

        return $this->getIssueProgress($sprintIssues);
    }

    protected function getFilterData($id = null, array $get = [])
    {
        if ($id === null) {
            throw new Exception('No filter ID provided', 1);
        }

        $get = array_merge([
            'expand' => ''
        ], $get);

        $response = $this->curlGet($this->apiUrl . $this->resourcesFilter . '/' . $id, $get);

        return json_decode($response, true);
    }

    protected function getSearchData(array $get = [])
    {
        $get = array_merge([
          'jql' => '',
          'startAt' => 0,
          'maxResults' => 50,
          'validateQuery' => true,
          'fiels' => '',
          'expand' => ''
        ], $get);

        $response = $this->curlGet(
            $this->apiUrl . $this->resourcesSearch . '/',
            $get
        );

        return json_decode($response, true);
    }

    public function getSprintNumber()
    {
        $get = [
          'maxResults' => 1,
          'state' => 'active'
        ];

        return $this->getSprint($get);
    }

    // Returns the first active sprint found for the board
    protected function getActiveSprintId()
    {
        $get = [
            'maxResults' => 1,
            'state' => 'active'
        ];

        $response = $this->getSprint($get);

        if (!isset($response['values']) || empty($response['values'])) {
            throw new Exception('No active sprints found');
        } else {
            return reset($response['values'])['id'];
        }
    }

    protected function getSprint(array $get = [])
    {
        $get = array_merge([
          'startAt' => 0,
          'maxResults' => 50,
          'state' => ''
        ], $get);

        $response = $this->curlGet(
            $this->agileUrl . 'board/' . $this->boardId . '/sprint/',
            $get
        );

        return json_decode($response, true);
    }

    protected function getSprintIssues($id = null, array $get = [])
    {
        if ($id === null) {
            throw new Exception('No sprint ID provided', 1);
        }

        $get = array_merge(
            [
                'startAt' => 0,
                'maxResults' => 50,
                'jql' => '',
                'validateQuery' => true,
                'fields' => '',
                'expand' => ''
            ],
            $get
        );

        $issues = [];

        do {
            $response = $this->curlGet(
                $this->agileUrl . 'board/' . $this->boardId . '/sprint/'
                    . $id . '/issue',
                $get
            );

            $response = json_decode($response, true);
            $issues = array_merge($issues, $response['issues']);
            $get['startAt'] += $get['maxResults'];
            sleep(1);
        } while (
            count($response['issues']) == $response['maxResults']
        );

        return $issues;
    }

    protected function getIssueProgress($issues)
    {
        $progress = [
            'total' => 0,
            'undefined' => 0,
            'new' => 0,
            'done' => 0,
            'intermediate' => 0
        ];

        $issueStatusMapping  = [
            1 => 'undefined',
            2 => 'new',
            3 => 'done',
            4 => 'intermediate'
        ];

        foreach ($issues as $issue) {
            $estimate = $issue['fields'][$this->storypointField];

            if ($estimate) {
                $status = $issueStatusMapping[$issue['fields']['status']['statusCategory']['id']];
                $progress[$status] += $estimate;
                $progress['total'] += $estimate;
            }
        }

        return $progress;
    }

    protected function curlPost($url, array $post = [], array $options = [])
    {
        $defaults = [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_POSTFIELDS => http_build_query($post),
            CURLOPT_USERPWD => env('CURLOPT_USERPWD')
        ];

        $ch = curl_init();

        curl_setopt_array($ch, ($options + $defaults));

        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }

    protected function curlGet($url, array $get = [], array $options = [])
    {
        $defaults = [
            CURLOPT_URL => $url . (strpos($url, '?') === false ? '?' : '')
                . http_build_query($get),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_USERPWD => env('CURLOPT_USERPWD')
        ];

        $ch = curl_init();

        curl_setopt_array($ch, ($options + $defaults));

        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }

        curl_close($ch);

        return $result;
    }
}
