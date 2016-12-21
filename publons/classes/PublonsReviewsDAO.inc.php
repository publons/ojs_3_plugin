<?php

/**
 * @file plugins/generic/publons/classes/PublonsReviewsDAO.inc.php
 *
 * Copyright (c) 2016 Publons Ltd.
 * Distributed under the GNU GPL v3.
 *
 * @class PublonsReviewsDAO
 * @ingroup plugins_generic_publons
 * @see PublonsReview
 *
 * @brief Operations for retrieving and modifying PublonsReviews objects.
 */
import('lib.pkp.classes.db.DAO');

class PublonsReviewsDAO extends DAO {

    /**
     * Retrieve a publons reviews by ID.
     * @param $publonsReviewsId int
     * @return PublonsReviews
     */
    function &getPublonsReviews($publonsReviewsId) {
        $result =& $this->retrieve(
            'SELECT * FROM publons_reviews WHERE publons_reviews_id = ?', $publonsReviewsId
        );

        $returner = null;
        if ($result->RecordCount() != 0) {
            $returner =& $this->_returnPublonsReviewsFromRow($result->GetRowAssoc(false));
        }
        $result->Close();
        return $returner;
    }

    /**
     * Get a list of localized field names
     * @return array
     */
    function getLocaleFieldNames() {
        return array('title', 'content');
    }

    /**
     * Internal function to return a PublonsReviews object from a row.
     * @param $row array
     * @return PublonsReviews
    */
    function &_returnPublonsReviewsFromRow(&$row) {
        $publonsPlugin =& PluginRegistry::getPlugin('generic', $this->parentPluginName);

        $publonsReviews = new PublonsReviews();
        $publonsReviews->setId($row['publons_reviews_id']);
        $publonsReviews->setJournalId($row['journal_id']);
        $publonsReviews->setArticleId($row['article_id']);
        $publonsReviews->setReviewerId($row['reviewer_id']);
        $publonsReviews->setReviewId($row['review_id']);
        $publonsReviews->setTitleEn($row['title_en']);
        $publonsReviews->setDateAdded($this->datetimeFromDB($row['date_added']));

        $this->getDataObjectSettings('publons_reviews_settings', 'publons_reviews_id', $row['publons_reviews_id'], $publonsReviews);

        return $publonsReviews;
    }

    /**
     * Insert a new review into the Publons.
     * @param $publonsReviews PublonsReviews
     * @return int
     */
    function insertObject(&$publonsReviews) {
        $ret = $this->update(
            sprintf('
                INSERT INTO publons_reviews
                    (journal_id,
                    article_id,
                    reviewer_id,
                    review_id,
                    title_en,
                    date_added)
                VALUES
                    (?, ?, ?, ?, ?, %s)',
                $this->datetimeToDB($publonsReviews->getDateAdded())
            ),
            array(
                $publonsReviews->getJournalId(),
                $publonsReviews->getArticleId(),
                $publonsReviews->getReviewerId(),
                $publonsReviews->getReviewId(),
                $publonsReviews->getTitleEn()
            )
        );
        $publonsReviews->setId($this->getInsertObjectId());
        $this->updateLocaleFields($publonsReviews);

        return $publonsReviews->getId();
    }

    /**
     * Update the localized settings for this object
     * @param $referral object
     */
    function updateLocaleFields(&$publonsReviews) {
        $this->updateDataObjectSettings('publons_reviews_settings', $publonsReviews, array(
            'publons_reviews_id' => $publonsReviews->getId()
        ));
    }

    /**
     * Update an existing data about reviews, publishing into the Publons.
     * @param $publonsReviews PublonsReviews object
     * @return boolean
     */
    function updateObject(&$publonsReviews) {
        $returner = $this->update(
            sprintf('UPDATE publons_reviews
                SET journal_id = ?,
                    article_id = ?,
                    reviewer_id = ?,
                    review_id = ?,
                    title_en = ?,
                    date_added = %s
                WHERE   publons_reviews_id = ?',
                $this->datetimeToDB($publonsReviews->getDateAdded())
            ),
            array(
                (int) $publonsReviews->getJournal(),
                (int) $publonsReviews->getArticleId(),
                (int) $publonsReviews->getReviewerId(),
                (int) $publonsReviews->getReviewId(),
                $publonsReviews->getTitleEn(),
                (int) $publonsReviews->getId()
            )
        );
        $this->updateLocaleFields($publonsReviews);
        return $returner;
    }

    /**
     * Delete a data about review into the Publons.
     * deleted.
     * @param $publonsReviews Referral
     * @return boolean
     */
    function deleteObject($publonsReviews) {
        return $this->deleteObjectById($publonsReviews->getId());
    }

    /**
     * Delete an object by ID.
     * @param $publonsReviewsId int
     * @return boolean
     */
    function deleteObjectById($publonsReviewsId) {
        $this->update('DELETE FROM publons_reviews_settings WHERE publons_reviews_id = ?', (int) $publonsReviewsId);
        return $this->update('DELETE FROM publons_reviews WHERE publons_reviews_id = ?', (int) $publonsReviewsId);
    }

    /**
     * Get the ID of the last inserted review into Publons.
     * @return int
     */
    function getInsertObjectId() {
        return $this->getInsertId('publons_reviews', 'publons_reviews_id');
    }


    /**
     * Return a submitted book for review id for a given article and journal.
     * @param $journalId int
     * @param $articleId int
     * @param $reviewerId int
     * @return int
     */
    function getPublonsReviewsIdByArticle($journalId, $articleId, $reviewerId) {

        $result =& $this->retrieve(
            'SELECT publons_reviews_id
                FROM publons_reviews
                WHERE article_id = ?
                AND journal_id = ?
                AND reviewer_id = ?',
            array(
                $articleId,
                $journalId,
                $reviewerId
            )
        );

        $returner = isset($result->fields[0]) && $result->fields[0] != 0 ? $result->fields[0] : null;

        $result->Close();
        unset($result);

        return $returner;
    }

        /**
     * Return a submitted book for review id for a given article and journal.
     * @param $reviewId int
     * @return int
     */
    function getPublonsReviewsIdByReviewId($reviewId) {

        $result =& $this->retrieve(
            'SELECT publons_reviews_id
                FROM publons_reviews
                WHERE review_id = ?',
            array(
                $reviewId
            )
        );

        $returner = isset($result->fields[0]) && $result->fields[0] != 0 ? $result->fields[0] : null;

        $result->Close();
        unset($result);

        return $returner;
    }

    /**
     * Retrieve an iterator of PublonsReviews for a particular journal ID,
     * optionally filtering by status.
     * @param $journalId int
     * @return object DAOResultFactory containing matching PublonsReviews
     */
    function &getPublonsReviewsByJournal($journalId, $rangeInfo = null, $sort) {
        $result =& $this->retrieveRange(
            "SELECT *
            FROM    publons_reviews
            WHERE   journal_id = ?
            ORDER BY '$sort'",
            $journalId,
            $rangeInfo
        );
        $returner = new DAOResultFactory($result, $this, '_returnPublonsReviewsFromRow');
        return $returner;
    }
}

?>
