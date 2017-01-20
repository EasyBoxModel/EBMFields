<?php

namespace EBMQ\Tests\Factory;

use EBMQ\Questionnaire\Questionnaire;
use EBMQ\Tests\Questionnaire\UserQuestionnaire;

class QuestionnaireFactory
{
    public static function get(Int $id): Questionnaire
    {
        return new UserQuestionnaire($id);
    }
}
