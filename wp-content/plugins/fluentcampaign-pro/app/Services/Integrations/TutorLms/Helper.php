<?php

namespace FluentCampaign\App\Services\Integrations\TutorLms;

class Helper
{
    public static function getCourses()
    {
        $courses = get_posts(array(
            'post_type' => 'course',
            'numberposts' => -1
        ));

        $formattedCourses = [];
        foreach ($courses as $course) {
            $formattedCourses[] = [
                'id'    => strval($course->ID),
                'title' => $course->post_title
            ];
        }

        return $formattedCourses;
    }

    public static function getMemberships()
    {
        $courses = get_posts(array(
            'post_type' => 'llms_membership',
            'numberposts' => -1
        ));

        $formattedCourses = [];
        foreach ($courses as $course) {
            $formattedCourses[] = [
                'id'    => strval($course->ID),
                'title' => $course->post_title
            ];
        }

        return $formattedCourses;
    }

    public static function getStudentAddress($userId)
    {
        return [
            'address_line_1' => get_user_meta($userId, 'llms_billing_address_1', true),
            'address_line_2' => get_user_meta($userId, 'llms_billing_address_2', true),
            'postal_code'    =>get_user_meta($userId, 'llms_billing_zip', true),
            'city'           => get_user_meta($userId, 'llms_billing_city', true),
            'state'          => get_user_meta($userId, 'llms_billing_state', true),
            'country'        => get_user_meta($userId, 'llms_billing_country', true),
        ];
    }

    public static function getLessonsByCourseGroup()
    {
        $courses = get_posts(array(
            'post_type' => 'course',
            'numberposts' => -1
        ));

        $groups = [];
        foreach ($courses as $course) {
             $group = [
                'title' => $course->post_title,
                 'slug' => $course->post_name.'_'.$course->ID,
                'options' => []
            ];

            $lmsCourse = llms_get_post($course->ID);

            $lessons = $lmsCourse->get_lessons('posts');

            foreach ($lessons as $lesson) {
                $group['options'][] = [
                    'id' => strval($lesson->ID),
                    'title' => $lesson->post_title
                ];
            }
            $groups[] = $group;
        }
        return $groups;
    }
}
