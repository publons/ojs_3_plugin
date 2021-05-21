<?php

/**
 * @file classes/migration/SwordSchemaMigration.inc.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SwordSchemaMigration
 * @brief Describe database table structures.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class PublonsSchemaMigration extends Migration {
        /**
         * Run the migrations.
         * @return void
         */
        public function up() {
        // Deposit points.
        Capsule::schema()->create('publons_reviews', function (Blueprint $table) {
            $table->bigInteger('publons_reviews_id')->autoIncrement();
            $table->bigInteger('journal_id');
            $table->bigInteger('submission_id');
            $table->bigInteger('reviewer_id');
            $table->bigInteger('review_id');
            $table->string('title_en', 255);
            $table->datetime('date_added');
        });

        // Locale-specific deposit point data
        Capsule::schema()->create('publons_reviews_settings', function (Blueprint $table) {
            $table->bigInteger('publons_reviews_id')->autoIncrement();
            $table->string('locale', 5)->default('');
            $table->string('setting_name', 255);
            $table->text('setting_value')->nullable();
            $table->string('setting_type', 6);
            $table->index(['publons_reviews_id'], 'publons_reviews_settings_publons_reviews_id');
            $table->unique(['publons_reviews_id', 'locale', 'setting_name'], 'publons_reviews_settings_pkey');
        });

    }
}
