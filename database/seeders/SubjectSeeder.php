<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Department;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get departments
        $bsit = Department::firstOrCreate(['name' => 'BSIT - Information Technology']);
        $bat = Department::firstOrCreate(['name' => 'BAT - Bachelor in Agricultural Technology']);
        $bsa = Department::firstOrCreate(['name' => 'BSA - Bachelor of Science in Agriculture']);
        $btled = Department::firstOrCreate(['name' => 'BTLED - Bachelor of Technology and Livelihood Education']);

        // Small helper to derive a department code prefix from department name
        $deptCode = function (Department $dept) {
            $parts = explode(' - ', $dept->name, 2);
            return strtoupper(trim($parts[0] ?? $dept->name));
        };

        // ================================
        // BSIT SUBJECTS
        // ================================
        $bsitSubjects = [
            ['subject_code' => 'IT111', 'subject_title' => 'Information Technology 111', 'description' => 'Introduction to Information Technology'],
            ['subject_code' => 'IT112', 'subject_title' => 'Information Technology 112', 'description' => 'Basic Computer Concepts'],
            ['subject_code' => 'IT212', 'subject_title' => 'Information Technology 212', 'description' => 'Advanced Programming Concepts'],
            ['subject_code' => 'IT213', 'subject_title' => 'Information Technology 213', 'description' => 'System Analysis and Design'],
            ['subject_code' => 'IT215', 'subject_title' => 'Information Technology 215', 'description' => 'Data Structures and Algorithms'],
            ['subject_code' => 'IT311', 'subject_title' => 'Information Technology 311', 'description' => 'Database Management Systems'],
            ['subject_code' => 'IT312', 'subject_title' => 'Information Technology 312', 'description' => 'Web Development'],
            ['subject_code' => 'IT314', 'subject_title' => 'Information Technology 314', 'description' => 'Network and Security Fundamentals'],
            ['subject_code' => 'IT315', 'subject_title' => 'Information Technology 315', 'description' => 'Software Engineering'],
            ['subject_code' => 'IT414', 'subject_title' => 'Information Technology 414', 'description' => 'Capstone Project / IT Seminar'],
            ['subject_code' => 'ICT313', 'subject_title' => 'Information and Communications Technology 313', 'description' => 'ICT in Education and Society'],
            ['subject_code' => 'ICT314', 'subject_title' => 'Information and Communications Technology 314', 'description' => 'Networking and Systems Integration'],
            ['subject_code' => 'ICT321', 'subject_title' => 'Information and Communications Technology 321', 'description' => 'Web Systems and Applications'],
            ['subject_code' => 'ICT329', 'subject_title' => 'Information and Communications Technology 329', 'description' => 'ICT Project Implementation'],
        ];

        // ================================
        // BAT & BSA SUBJECTS (Shared)
        // ================================
        $bat_bsa_subjects = [
            ['subject_code' => 'AFAE125', 'subject_title' => 'Agricultural and Fishery Arts Education 125', 'description' => 'Introduction to AFAE principles'],
            ['subject_code' => 'AFAE319', 'subject_title' => 'Agricultural and Fishery Arts Education 319', 'description' => 'Advanced AFAE studies'],
            ['subject_code' => 'AgEcon111', 'subject_title' => 'Agricultural Economics 111', 'description' => 'Economics in agriculture and agribusiness'],
            ['subject_code' => 'AgExt212', 'subject_title' => 'Agricultural Extension 212', 'description' => 'Extension methods and practices'],
            ['subject_code' => 'AgEx411', 'subject_title' => 'Agricultural Extension 411', 'description' => 'Advanced agricultural extension'],
            ['subject_code' => 'Agri111', 'subject_title' => 'Agriculture 111', 'description' => 'Introduction to agriculture'],
            ['subject_code' => 'Agri212', 'subject_title' => 'Agriculture 212', 'description' => 'Crop production management'],
            ['subject_code' => 'Agri213', 'subject_title' => 'Agriculture 213', 'description' => 'Animal husbandry practices'],
            ['subject_code' => 'AgriIT211a', 'subject_title' => 'Agricultural Information Technology 211a', 'description' => 'IT applications in agriculture (part A)'],
            ['subject_code' => 'AgriIT211b', 'subject_title' => 'Agricultural Information Technology 211b', 'description' => 'IT applications in agriculture (part B)'],
            ['subject_code' => 'AgTech311', 'subject_title' => 'Agricultural Technology 311', 'description' => 'Modern agricultural technologies'],
            ['subject_code' => 'AgTech312', 'subject_title' => 'Agricultural Technology 312', 'description' => 'Agricultural innovations and trends'],
            ['subject_code' => 'AgTech313', 'subject_title' => 'Agricultural Technology 313', 'description' => 'Farm mechanization and operations'],
            ['subject_code' => 'AgTech413', 'subject_title' => 'Agricultural Technology 413', 'description' => 'Sustainable farming systems'],
            ['subject_code' => 'ASM313', 'subject_title' => 'Animal Science Management 313', 'description' => 'Livestock management and production'],
            ['subject_code' => 'SOIL211a', 'subject_title' => 'Soil Science 211a', 'description' => 'Introduction to soil science (part A)'],
            ['subject_code' => 'SOIL211b', 'subject_title' => 'Soil Science 211b', 'description' => 'Advanced soil fertility (part B)'],
            ['subject_code' => 'CS212a', 'subject_title' => 'Crop Science 212a', 'description' => 'Crop science fundamentals'],
            ['subject_code' => 'CS212b', 'subject_title' => 'Crop Science 212b', 'description' => 'Advanced crop production'],
            ['subject_code' => 'CS312', 'subject_title' => 'Crop Science 312', 'description' => 'Field crop management'],
            ['subject_code' => 'CS314', 'subject_title' => 'Crop Science 314', 'description' => 'Crop breeding and genetics'],
            ['subject_code' => 'CS415', 'subject_title' => 'Crop Science 415', 'description' => 'Crop protection and pest control'],
            ['subject_code' => 'PHT412', 'subject_title' => 'Post-Harvest Technology 412', 'description' => 'Post-harvest handling and storage'],
        ];

        // ================================
        // BTLED SUBJECTS
        // ================================
        $btledSubjects = [
            ['subject_code' => 'EDU111', 'subject_title' => 'Education 111', 'description' => 'Foundations of education'],
            ['subject_code' => 'EDU112', 'subject_title' => 'Education 112', 'description' => 'Learner-centered teaching principles'],
            ['subject_code' => 'EDU211', 'subject_title' => 'Education 211', 'description' => 'Educational psychology'],
            ['subject_code' => 'EDU212', 'subject_title' => 'Education 212', 'description' => 'Curriculum development and assessment'],
            ['subject_code' => 'EXP111', 'subject_title' => 'Exploratory 111', 'description' => 'Exploratory teaching methodologies'],
            ['subject_code' => 'EXP112', 'subject_title' => 'Exploratory 112', 'description' => 'Advanced exploratory instruction'],
            ['subject_code' => 'EXP113', 'subject_title' => 'Exploratory 113', 'description' => 'Technology in exploratory teaching'],
            ['subject_code' => 'HEE318', 'subject_title' => 'Home Economics Education 318', 'description' => 'Home economics instruction and practice'],
            ['subject_code' => 'AFAE125', 'subject_title' => 'Agricultural and Fishery Arts Education 125', 'description' => 'Agriculture and fisheries for BTLED majors'],
        ];

        // ================================
        // GENERAL EDUCATION SUBJECTS
        // ================================
        $genEdSubjects = [
            ['subject_code' => 'AHApp', 'subject_title' => 'Art History Appreciation', 'description' => 'Study of art history and appreciation'],
            ['subject_code' => 'ArtApp', 'subject_title' => 'Art Appreciation', 'description' => 'Fundamentals of art and design'],
            ['subject_code' => 'LITE', 'subject_title' => 'Literature', 'description' => 'World and Philippine literature'],
            ['subject_code' => 'Math121', 'subject_title' => 'Mathematics 121', 'description' => 'Basic and applied mathematics'],
            ['subject_code' => 'MMW', 'subject_title' => 'Mathematics in the Modern World', 'description' => 'Applications of math in daily life'],
            ['subject_code' => 'NSTP101', 'subject_title' => 'National Service Training Program 101', 'description' => 'Community service and citizenship training'],
            ['subject_code' => 'PATHFIT1', 'subject_title' => 'Pathways to Fitness 1', 'description' => 'Physical fitness and health awareness'],
            ['subject_code' => 'PATHFIT3', 'subject_title' => 'Pathways to Fitness 3', 'description' => 'Physical activity and wellness'],
            ['subject_code' => 'PurCom', 'subject_title' => 'Purposive Communication', 'description' => 'Effective communication for various purposes'],
            ['subject_code' => 'RPH', 'subject_title' => 'Readings in Philippine History', 'description' => 'Study of primary sources on Philippine history'],
            ['subject_code' => 'STS', 'subject_title' => 'Science, Technology and Society', 'description' => 'Relationship between science, tech, and society'],
            ['subject_code' => 'TCW', 'subject_title' => 'The Contemporary World', 'description' => 'Globalization and modern issues'],
            ['subject_code' => 'UTS', 'subject_title' => 'Understanding the Self', 'description' => 'Self-awareness and personal development'],
        ];

        // Insert subjects by department
        foreach ($bsitSubjects as $subject) {
            Subject::updateOrCreate(
                ['subject_code' => $subject['subject_code']],
                [
                    'subject_title' => $subject['subject_title'],
                    'description' => $subject['description'],
                    'department_id' => $bsit->id,
                ]
            );
        }

        foreach ($bat_bsa_subjects as $subject) {
            // Use department-suffixed codes to preserve uniqueness across table
            $batCode = $subject['subject_code'] . '-' . $deptCode($bat);
            $bsaCode = $subject['subject_code'] . '-' . $deptCode($bsa);

            Subject::updateOrCreate(
                ['subject_code' => $batCode],
                [
                    'subject_title' => $subject['subject_title'],
                    'description' => $subject['description'],
                    'department_id' => $bat->id,
                ]
            );

            Subject::updateOrCreate(
                ['subject_code' => $bsaCode],
                [
                    'subject_title' => $subject['subject_title'],
                    'description' => $subject['description'],
                    'department_id' => $bsa->id,
                ]
            );
        }

        foreach ($btledSubjects as $subject) {
            Subject::updateOrCreate(
                ['subject_code' => $subject['subject_code']],
                [
                    'subject_title' => $subject['subject_title'],
                    'description' => $subject['description'],
                    'department_id' => $btled->id,
                ]
            );
        }

        // Add General Education to all departments
        $departments = [$bsit, $bat, $bsa, $btled];
        foreach ($departments as $dept) {
            $prefix = $deptCode($dept);
            foreach ($genEdSubjects as $subject) {
                $code = $subject['subject_code'] . '-' . $prefix; // ensure uniqueness across departments
                Subject::updateOrCreate(
                    ['subject_code' => $code],
                    [
                        'subject_title' => $subject['subject_title'],
                        'description' => $subject['description'],
                        'department_id' => $dept->id,
                    ]
                );
            }
        }
    }
}
