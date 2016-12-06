<?php

/**
 * @file plugins/generic/publons/pages/PublonsHandler.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PublonsHandler
 * @ingroup plugins_generic_publons
 *
 * @brief Handle Publons requests
 */


import('pages.sectionEditor.SectionEditorHandler');
import('pages.reviewer.ReviewerHandler');
import('classes.handler.Handler');

class PublonsHandler extends Handler {

    /**
     * Display
     * @param array $args
     * @param Request $request
     */
    function exportReviews($args, &$request) {

        $plugin =& PluginRegistry::getPlugin('generic', PUBLONS_PLUGIN_NAME);
        $templateMgr =& TemplateManager::getManager();

        $reviewId = intval($request->getUserVar('reviewId'));
        $publonsReviewsDao =& DAORegistry::getDAO('PublonsReviewsDAO');
        $articleCommentDao =& DAORegistry::getDAO('ArticleCommentDAO');
        $reviewerSubmissionDao =& DAORegistry::getDAO('ReviewerSubmissionDAO');

        $exported =& $publonsReviewsDao->getPublonsReviewsIdByReviewId($reviewId);

        // Check that the review hasn't been exported already
        if ($exported){
            $templateMgr->assign('info', __('plugins.generic.publons.alreadyExported'));
            $templateMgr->display($plugin->getTemplatePath() . 'export.tpl');
            return;
        }

        $reviewSubmission = $reviewerSubmissionDao->getReviewerSubmission($reviewId);

        // Check that the review has been submitted to the editor
        if (($reviewSubmission->getRecommendation() === null) || ($reviewSubmission->getRecommendation() === '')){
            $templateMgr->assign('info', __('plugins.generic.publons.notSubmitted'));
            $templateMgr->display($plugin->getTemplatePath() . 'export.tpl');
            return;
        }

        $journalId = $reviewSubmission->getJournalId();
        $articleId = $reviewSubmission->getArticleId();
        $reviewerId = $reviewSubmission->getReviewerId();

        $rtitle = $reviewSubmission->getLocalizedTitle();
        $rtitle_en = $reviewSubmission->getTitle('en_US');

        $user =& Request::getUser();
        $rname = $user->getFullName();
        $remail = $user->getEmail();

        // Check that user is person who wrote review
        if ($user->getId() !== $reviewerId){
            $templateMgr->assign('info', __('plugins.generic.publons.invalidUser'));
            $templateMgr->display($plugin->getTemplatePath() . 'export.tpl');
            return;
        }

        $reviewAssignmentDao =& DAORegistry::getDAO('ReviewAssignmentDAO');
        $reviewAssignment =& $reviewAssignmentDao->getReviewAssignment($reviewSubmission->getId(), $reviewerId, $reviewSubmission->getCurrentRound());
        $body = '';
        if (!$reviewAssignment->getCancelled()) {
            // Get the comments associated with this review assignment
            $articleComments =& $articleCommentDao->getArticleComments($reviewSubmission->getId(), COMMENT_TYPE_PEER_REVIEW, $reviewAssignment->getId());

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
        $publonsReviews->setArticleId($articleId);
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
        $data["content"] = $body;
        $data["complete_date"]["day"] = date('d');
        $data["complete_date"]["month"] = date('m');
        $data["complete_date"]["year"] = date('Y');

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

        $responseCodes = array(
            '200' => 'OK - Success.',
            '201' => 'Created - Success.',
            '400' => 'Bad Request - You are doing something wrong.',
            '403' => 'Forbidden - You do not have permission to do this.',
            '404' => 'Not Found - Resource not found.',
            '405' => 'Method Not Allowed - You`re probably trying to post to a resource that only supports GET.',
            '500' => '500 Internal Server Error - Please contact api@publons.com.'
        );

        $templateMgr->assign('result',$returned['result']);
        $templateMgr->assign('status',$returned['status'].' '.$responseCodes[$returned['status']]);
        $templateMgr->assign('error', $returned['error']);

        # If success then save into database
        if (($returned['status'] >= 200) && ($returned['status'] < 300)){
            $publonsReviewsDao->insertObject($publonsReviews);
        }

        $templateMgr->assign('rname',$rname);
        $templateMgr->assign('rbody',$rbody);
        $templateMgr->assign('rtitle',$rtitle);
        $templateMgr->assign('rtitle_en',$rtitle_en);
        $templateMgr->assign('remail',$remail);

        $templateMgr->display($plugin->getTemplatePath() . 'export.tpl');
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
            'result' => $httpResult,
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
