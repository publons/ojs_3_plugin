<?php

/**
 * @file plugins/generic/publons/pages/PublonsHandler.inc.php
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class PublonsHandler
 * @ingroup plugins_generic_publons
 *
 * @brief Handle Publons requests
 */


import('pages.reviewer.ReviewerHandler');
import('classes.handler.Handler');
import('lib.pkp.classes.core.JSONMessage');

class PublonsHandler extends Handler {

    /** @var PublonsPlugin The publons plugin */

    static $plugin;

    static function setPlugin($plugin) {
        self::$plugin = $plugin;
    }

    /**
     * Confirm you want to export the review (GET) then export it (POST)
     * @param array $args
     * @param Request $request
     */
    function exportReview($args, $request) {
        $plugin =self::$plugin;
        $templateMgr =& TemplateManager::getManager();
        $templateMgr->addStyleSheet(Request::getBaseUrl() . '/' . $plugin->getStyleSheet());
        $templateMgr->addStyleSheet(Request::getBaseUrl() . '/' . $plugin->getPluginPath() . '/styles/publons-page.css');
        $templateMgr->addStyleSheet('https://fonts.googleapis.com/css?family=Roboto');

        $reviewId = intval($args[0]);

        $publonsReviewsDao =& DAORegistry::getDAO('PublonsReviewsDAO');
        $submissionCommentDao =& DAORegistry::getDAO('SubmissionCommentDAO');
        $reviewerSubmissionDao =& DAORegistry::getDAO('ReviewerSubmissionDAO');

        $exported =& $publonsReviewsDao->getPublonsReviewsIdByReviewId($reviewId);

        $reviewSubmission = $reviewerSubmissionDao->getReviewerSubmission($reviewId);

        $reviewerId = $reviewSubmission->getReviewerId();

        $user =& Request::getUser();

        if ($exported) {
            // Check that the review hasn't been exported already
            $templateMgr->assign('info', __('plugins.generic.publons.export.error.alreadyExported'));
            return $templateMgr->fetchJson($plugin->getTemplatePath() . 'export.tpl');

        } elseif (($reviewSubmission->getRecommendation() === null) || ($reviewSubmission->getRecommendation() === '')) {
            // Check that the review has been submitted to the editor
            $templateMgr->assign('info', __('plugins.generic.publons.export.error.reviewNotSubmitted'));
            return $templateMgr->fetchJson($plugin->getTemplatePath() . 'export.tpl');

        } elseif ($user->getId() !== $reviewerId) {
            // Check that user is person who wrote review
            $templateMgr->assign('info', __('plugins.generic.publons.export.error.invalidUser'));
            return $templateMgr->fetchJson($plugin->getTemplatePath() . 'export.tpl');
        }



        if ($request->isGet()) {

            $router = $request->getRouter();
            $templateMgr->assign('reviewId', $reviewId);
            $templateMgr->assign('pageURL', $router->url($request, null, null, 'exportReview', array('reviewId' =>  $reviewId)));


            $templateMgr->addStyleSheet(Request::getBaseUrl() . '/' . $plugin->getPluginPath() . '/styles/publons-page.css');
            $templateMgr->addStyleSheet('https://fonts.googleapis.com/css?family=Roboto');
            return $templateMgr->fetchJson($plugin->getTemplatePath() . 'publonsExportReviewForm.tpl');
        }
        elseif ($request->isPost())
        {
            $journalId = $reviewSubmission->getJournalId();
            $submissionId = $reviewSubmission->getId();
            $rtitle = $reviewSubmission->getLocalizedTitle();
            $rtitle_en = $reviewSubmission->getTitle('en_US');
            $rname = $user->getFullName();
            $remail = $user->getEmail();

            $reviewAssignmentDao =& DAORegistry::getDAO('ReviewAssignmentDAO');
            $reviewAssignment =& $reviewAssignmentDao->getReviewAssignment($reviewSubmission->getId(), $reviewerId, $reviewSubmission->getCurrentRound());
            $body = '';
            if (!$reviewAssignment->getCancelled()) {
                // Get the comments associated with this review assignment
                $articleComments =& $submissionCommentDao->getSubmissionComments($reviewSubmission->getId(), COMMENT_TYPE_PEER_REVIEW, $reviewAssignment->getId());

                if($articleComments) {
                    if (is_array($articleComments)) {
                        foreach ($articleComments as $comment) {
                            // If the comment is viewable by the author, then add the comment.
                            if ($comment->getViewable()) $body .= String::html2text($comment->getComments()) . "\n\n";
                        }
                    }
                }
                if ($reviewFormId = $reviewAssignment->getReviewFormId()) {

                    $reviewId = $reviewAssignment->getId();
                    $reviewFormResponseDao =& DAORegistry::getDAO('ReviewFormResponseDAO');
                    $reviewFormElementDao =& DAORegistry::getDAO('ReviewFormElementDAO');
                    $reviewFormElements =& $reviewFormElementDao->getReviewFormElements($reviewFormId);

                    foreach ($reviewFormElements as $reviewFormElement) if ($reviewFormElement->getIncluded()) {

                        $body .= String::html2text($reviewFormElement->getLocalizedQuestion()) . ": \n";
                        $reviewFormResponse = $reviewFormResponseDao->getReviewFormResponse($reviewId, $reviewFormElement->getId());

                        if ($reviewFormResponse) {

                            $possibleResponses = $reviewFormElement->getLocalizedPossibleResponses();
                            if (in_array($reviewFormElement->getElementType(), $reviewFormElement->getMultipleResponsesElementTypes())) {
                                if ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_CHECKBOXES) {
                                    foreach ($reviewFormResponse->getValue() as $value) {
                                        $body .= "\t" . String::html2text($possibleResponses[$value-1]['content']) . "\n";
                                    }
                                } else {
                                    $body .= "\t" . String::html2text($possibleResponses[$reviewFormResponse->getValue()-1]['content']) . "\n";
                                }
                                $body .= "\n";
                            } else {
                                $body .= "\t" . $reviewFormResponse->getValue() . "\n\n";
                            }
                        }
                    }
                }
            }
            $body = str_replace("\r", '', $body);
            $body = str_replace("\n", '\r\n', $body);

            $auth_key = $plugin->getSetting($journalId, 'auth_key');
            $auth_token = $plugin->getSetting($journalId, 'auth_token');

            $plugin->import('classes.PublonsReviews');

            $locale = AppLocale::getLocale();

            $publonsReviews = new PublonsReviews();

            $publonsReviews->setJournalId($journalId);
            $publonsReviews->setSubmissionId($submissionId);
            $publonsReviews->setReviewerId($reviewerId);
            $publonsReviews->setReviewId($reviewId);
            $publonsReviews->setTitleEn($rtitle_en);
            $publonsReviews->setDateAdded(Core::getCurrentDate());

            $publonsReviews->setTitle($rtitle, $locale);
            $publonsReviews->setContent($body, $locale);


            $publonsReviewsDao = new PublonsReviewsDAO();
            DAORegistry::registerDAO('PublonsReviewsDAO', $publonsReviewsDao);

            $headers = array(
                "Authorization: Token ". $auth_token,
                'Content-Type: application/json'
            );

            $data = array();
            $data["key"] = $auth_key;
            $data["reviewer"]["name"] = $rname;
            $data["reviewer"]["email"] = $remail;
            $data["publication"]["title"] = $rtitle;
            $data["complete_date"]["day"] = date('d');
            $data["complete_date"]["month"] = date('m');
            $data["complete_date"]["year"] = date('Y');

            // Don't send content if it is empty
            if ($body !== '')
                $data["content"] = $body;

            $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);

            $templateMgr->assign('json_data',$json_data);

            if (is_null($_SERVER["HTTP_PUBLONS_URL"])) {
                $url = "https://publons.com/api/v2/review/";
            } else {
                $url = $_SERVER["HTTP_PUBLONS_URL"]."/api/v2/review/";
            }

            $options = array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $json_data
            );

            $returned = $this->_curlPost($options);

            // If success then save into database
            if (($returned['status'] >= 200) && ($returned['status'] < 300)){
                $publonsReviewsDao->insertObject($publonsReviews);
            }

            $templateMgr->assign('status',$returned['status']);

            if ($returned['status'] == 201){
                $templateMgr->assign('serverAction',$returned['result']['action']);
                if (is_null($_SERVER["HTTP_PUBLONS_URL"])) {
                    $claimUrl = "https://publons.com/review/credit/" . $returned['result']['token'] . "/claim/";
                } else {
                    $claimUrl = $_SERVER["HTTP_PUBLONS_URL"]."/review/credit/" . $returned['result']['token'] . "/claim/";
                }

                $templateMgr->assign('claimURL', $claimUrl);
            }

            $templateMgr->assign('result',$returned['result']);
            $templateMgr->assign('error', $returned['error']);
            return $templateMgr->fetchJson($plugin->getTemplatePath() . 'export.tpl');
        }


    }

    /**
     * Post a request to a resource using CURL
     * @param $url string
     * @param $headers array
     * @return array
     */
    function _curlPost($curlopt) {

        $curl = curl_init();
        curl_setopt_array($curl, $curlopt);

        $httpResult = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $httpError = curl_error($curl);
        curl_close ($curl);
        return array(
            'status' => $httpStatus,
            'result' => json_decode($httpResult, true),
            'error'  => $httpError
        );
    }

        /**
     * Get whether curl is available
     * @return boolean
     */
    function curlInstalled() {
        return function_exists('curl_version');
    }
}


?>
