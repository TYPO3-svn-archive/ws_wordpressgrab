<?php
namespace Ws\WsWordpressgrab\Task;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Nikolay Orlenko <info@web-spectr.com>, Web.Spectr http://web-spectr.com
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package wswordpressgrab
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class GrabberTaskAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface {

	/**
	 * Render additional information fields within the scheduler backend.
	 *
	 * @param array $taskInfo Array information of task to return
	 * @param ValidatorTask $task Task object
	 * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule Reference to the BE module of the Scheduler
	 * @return array Additional fields
	 * @see \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface->getAdditionalFields($taskInfo, $task, $schedulerModule)
	 */
	public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {
		$additionalFields = array();

        /** @var \Ws\WsWordpressgrab\Task\GrabberTask $task  */

		if (empty($taskInfo['host'])) {
			if ($schedulerModule->CMD == 'add') {
				$taskInfo['host'] = '';
			} elseif ($schedulerModule->CMD == 'edit') {
				$taskInfo['host'] = $task->getHost();
			} else {
				$taskInfo['host'] = $task->getHost();
			}
		}
        if (empty($taskInfo['username'])) {
            if ($schedulerModule->CMD == 'add') {
                $taskInfo['username'] = '';
            } elseif ($schedulerModule->CMD == 'edit') {
                $taskInfo['username'] = $task->getUsername();
            } else {
                $taskInfo['username'] = $task->getUsername();
            }
        }
        if (empty($taskInfo['password'])) {
            if ($schedulerModule->CMD == 'add') {
                $taskInfo['password'] = '';
            } elseif ($schedulerModule->CMD == 'edit') {
                $taskInfo['password'] = $task->getPassword();
            } else {
                $taskInfo['password'] = $task->getPassword();
            }
        }
        if (empty($taskInfo['database'])) {
            if ($schedulerModule->CMD == 'add') {
                $taskInfo['database'] = '';
            } elseif ($schedulerModule->CMD == 'edit') {
                $taskInfo['database'] = $task->getDatabase();
            } else {
                $taskInfo['database'] = $task->getDatabase();
            }
        }
        if (empty($taskInfo['table_prefix'])) {
            if ($schedulerModule->CMD == 'add') {
                $taskInfo['table_prefix'] = 'wp_';
            } elseif ($schedulerModule->CMD == 'edit') {
                $taskInfo['table_prefix'] = $task->getTablePrefix();
            } else {
                $taskInfo['table_prefix'] = $task->getTablePrefix();
            }
        }

        if (empty($taskInfo['categories'])) {
            if ($schedulerModule->CMD == 'add') {
                $taskInfo['categories'] = '';
            } elseif ($schedulerModule->CMD == 'edit') {
                $taskInfo['categories'] = $task->getCategories();
            } else {
                $taskInfo['categories'] = $task->getCategories();
            }
        }

        if (empty($taskInfo['pid'])) {
            if ($schedulerModule->CMD == 'add') {
                $taskInfo['pid'] = '1';
            } elseif ($schedulerModule->CMD == 'edit') {
                $taskInfo['pid'] = $task->getPid();
            } else {
                $taskInfo['pid'] = $task->getPid();
            }
        }

        $fieldId = 'task_host';
        $fieldCode = '<input type="text"  name="tx_scheduler[grabber][host]" id="' . $fieldId . '" value="' .
            htmlspecialchars($taskInfo['host']) . '" />';
        $label = $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.host');
        $label = \TYPO3\CMS\Backend\Utility\BackendUtility::wrapInHelp('grabber', $fieldId, $label);
        $additionalFields[$fieldId] = array(
            'code' => $fieldCode,
            'label' => $label
        );

        $fieldId = 'task_username';
        $fieldCode = '<input type="text"  name="tx_scheduler[grabber][username]" id="' . $fieldId . '" value="' .
            htmlspecialchars($taskInfo['username']) . '" />';
        $label = $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.username');
        $label = \TYPO3\CMS\Backend\Utility\BackendUtility::wrapInHelp('grabber', $fieldId, $label);
        $additionalFields[$fieldId] = array(
            'code' => $fieldCode,
            'label' => $label
        );

        $fieldId = 'task_password';
        $fieldCode = '<input type="text"  name="tx_scheduler[grabber][password]" id="' . $fieldId . '" value="' .
            htmlspecialchars($taskInfo['password']) . '" />';
        $label = $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.password');
        $label = \TYPO3\CMS\Backend\Utility\BackendUtility::wrapInHelp('grabber', $fieldId, $label);
        $additionalFields[$fieldId] = array(
            'code' => $fieldCode,
            'label' => $label
        );

        $fieldId = 'task_database';
        $fieldCode = '<input type="text"  name="tx_scheduler[grabber][database]" id="' . $fieldId . '" value="' .
            htmlspecialchars($taskInfo['database']) . '" />';
        $label = $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.database');
        $label = \TYPO3\CMS\Backend\Utility\BackendUtility::wrapInHelp('grabber', $fieldId, $label);
        $additionalFields[$fieldId] = array(
            'code' => $fieldCode,
            'label' => $label
        );

        $fieldId = 'task_table_prefix';
        $fieldCode = '<input type="text"  name="tx_scheduler[grabber][table_prefix]" id="' . $fieldId . '" value="' .
            htmlspecialchars($taskInfo['table_prefix']) . '" />';
        $label = $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.table_prefix');
        $label = \TYPO3\CMS\Backend\Utility\BackendUtility::wrapInHelp('grabber', $fieldId, $label);
        $additionalFields[$fieldId] = array(
            'code' => $fieldCode,
            'label' => $label
        );

        $fieldId = 'task_categories';
        $fieldCode = '<input type="text"  name="tx_scheduler[grabber][categories]" id="' . $fieldId . '" value="' .
            htmlspecialchars($taskInfo['categories']) . '" />';
        $label = $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.categories');
        $label = \TYPO3\CMS\Backend\Utility\BackendUtility::wrapInHelp('grabber', $fieldId, $label);
        $additionalFields[$fieldId] = array(
            'code' => $fieldCode,
            'label' => $label
        );

        $fieldId = 'task_pid';
        $fieldCode = '<input type="text"  name="tx_scheduler[grabber][pid]" id="' . $fieldId . '" value="' .
            htmlspecialchars($taskInfo['pid']) . '" />';
        $label = $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.pid');
        $label = \TYPO3\CMS\Backend\Utility\BackendUtility::wrapInHelp('grabber', $fieldId, $label);
        $additionalFields[$fieldId] = array(
            'code' => $fieldCode,
            'label' => $label
        );

		return $additionalFields;
	}


	/**
	 * This method checks any additional data that is relevant to the specific task.
	 * If the task class is not relevant, the method is expected to return TRUE.
	 *
	 * @param array $submittedData Reference to the array containing the data submitted by the user
	 * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule Reference to the BE module of the Scheduler
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {
		$isValid = TRUE;

        if (!$submittedData['grabber']['host']) {
            $isValid = FALSE;
            $schedulerModule->addMessage(
                $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.empty.host'),
                \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
            );
        }

        if (!$submittedData['grabber']['username']) {
            $isValid = FALSE;
            $schedulerModule->addMessage(
                $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.empty.username'),
                \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
            );
        }

        if (!$submittedData['grabber']['database']) {
            $isValid = FALSE;
            $schedulerModule->addMessage(
                $GLOBALS['LANG']->sL('LLL:EXT:ws_wordpressgrab/Resources/Private/Language/locallang.xlf:tasks.grabber.empty.database'),
                \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
            );
        }

		return $isValid;
	}

	/**
	 * This method is used to save any additional input into the current task object
	 * if the task class matches.
	 *
	 * @param array $submittedData Array containing the data submitted by the user
	 * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task Reference to the current task object
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
        /** @var $task \Ws\WsWordpressgrab\Task\GrabberTask */
		$task->setHost($submittedData['grabber']['host']);
		$task->setUsername($submittedData['grabber']['username']);
		$task->setPassword($submittedData['grabber']['password']);
        $task->setDatabase($submittedData['grabber']['database']);
        $task->setTablePrefix($submittedData['grabber']['table_prefix']);
        $task->setCategories($submittedData['grabber']['categories']);
        $task->setPid($submittedData['grabber']['pid']);
	}

}