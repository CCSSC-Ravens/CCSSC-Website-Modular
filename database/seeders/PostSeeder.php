<?php

namespace Database\Seeders;

use App\Models\OrganizationUser;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first organization user, or create one if none exists
        $author = OrganizationUser::first();
        
        if (!$author) {
            // Create a default organization user for posts if none exists
            $author = OrganizationUser::create([
                'name' => 'CCSSC Admin',
                'email' => 'admin@ccssc.edu.ph',
                'password' => bcrypt('password'),
                'position' => 'Content Manager',
                'permission' => 'editor',
            ]);
        }

        $posts = [
            [
                'title' => 'Sa Pagitan Microfilm Bags 1st Runner-Up at RAITE 2025',
                'content' => '<p>The College of Computer Studies proudly congratulates its student filmmakers for securing <strong>First Runner-Up</strong> in the Micro Short Film Contest at the Regional Assembly on Information Technology Education (RAITE) in Cabanatuan City, Nueva Ecija.</p>
                
                <p>The entry "<em>Sa Pagitan (Sumpa Kita)</em>", directed by <strong>Eizen Rodriguez</strong>, also received the <strong>People\'s Choice Award</strong> and earned recognition for <strong>Best Actress</strong>, awarded to <strong>Ms. Erica Mae Camintoy (BSCS 2)</strong>.</p>
                
                <p>These achievements highlight the talent and dedication of the cast and crew, bringing pride to the CCS community and the institution. The film explores themes of sacrifice and commitment, resonating deeply with audiences and judges alike.</p>
                
                <h3>Cast and Crew</h3>
                <ul>
                    <li><strong>Director:</strong> Eizen Rodriguez</li>
                    <li><strong>Lead Actress:</strong> Erica Mae Camintoy</li>
                    <li><strong>Producer:</strong> CCS Student Council</li>
                    <li><strong>Editor:</strong> Mark Anthony Santos</li>
                </ul>
                
                <p>Congratulations to everyone involved in this remarkable achievement!</p>',
                'thumbnail' => null,
            ],
            [
                'title' => 'CCS Bags Silver Medal in SkyDev Hackathon 2025',
                'content' => '<p>The Gordon College - College of Computer Studies team has secured a <strong>silver medal</strong> at the prestigious <strong>SkyDev Hackathon 2025</strong>, competing against top universities across the region.</p>
                
                <p>The team developed an innovative solution for sustainable campus management using IoT and machine learning technologies. Their project, named "<em>GreenCampus AI</em>", impressed judges with its practical applications and technical excellence.</p>
                
                <h3>Team Members</h3>
                <ul>
                    <li>John Paul Reyes - Team Lead</li>
                    <li>Maria Santos - Backend Developer</li>
                    <li>Carlos Dela Cruz - Frontend Developer</li>
                    <li>Ana Rodriguez - UI/UX Designer</li>
                </ul>
                
                <p>This achievement demonstrates the technical prowess and innovative spirit of our CCS students. The team spent 48 hours developing their solution during the hackathon, showcasing their dedication and teamwork.</p>
                
                <blockquote>
                    <p>"This experience taught us the value of collaboration and thinking outside the box. We\'re proud to represent Gordon College."</p>
                    <cite>- John Paul Reyes, Team Lead</cite>
                </blockquote>',
                'thumbnail' => null,
            ],
            [
                'title' => 'CCS Ravens Dominate SkyDev Mobile Legends Competition',
                'content' => '<p>The CCS Ravens esports team has brought glory to Gordon College by <strong>winning the championship</strong> at the SkyDev Mobile Legends: Bang Bang Competition 2025!</p>
                
                <p>After an intense tournament spanning three days, our team emerged victorious against 16 competing universities. The finals match was a nail-biting 3-2 victory against the defending champions.</p>
                
                <h3>Championship Roster</h3>
                <ul>
                    <li><strong>EXP Lane:</strong> Miguel "Phoenix" Torres</li>
                    <li><strong>Jungler:</strong> Kevin "Shadow" Mendoza</li>
                    <li><strong>Mid Lane:</strong> Patrick "Storm" Garcia</li>
                    <li><strong>Gold Lane:</strong> Ryan "Sniper" Cruz</li>
                    <li><strong>Roamer:</strong> James "Shield" Santos</li>
                </ul>
                
                <p>The team will represent our region in the national collegiate championships next month. Let\'s all show our support for the CCS Ravens!</p>
                
                <p><strong>#GoCCSRavens #ChampionsOfCCS</strong></p>',
                'thumbnail' => null,
            ],
            [
                'title' => 'CCSSC Launches New Student Portal for Academic Year 2025-2026',
                'content' => '<p>The CCS Student Council is excited to announce the launch of our new <strong>Student Portal</strong>, designed to enhance the student experience and streamline access to academic resources.</p>
                
                <h3>Key Features</h3>
                <ul>
                    <li><strong>Academic Calendar:</strong> Never miss important dates and deadlines</li>
                    <li><strong>Event Registration:</strong> Easy sign-up for workshops, seminars, and competitions</li>
                    <li><strong>Resource Library:</strong> Access to study materials and past exam papers</li>
                    <li><strong>Community Forum:</strong> Connect with fellow students and share knowledge</li>
                    <li><strong>Project Showcase:</strong> Display your projects and get feedback</li>
                </ul>
                
                <p>The portal was developed by CCS students under the guidance of the IT Committee, showcasing the practical skills our students acquire during their studies.</p>
                
                <p>Access the portal now and explore all the features designed to support your academic journey!</p>',
                'thumbnail' => null,
            ],
            [
                'title' => 'Annual Tech Summit 2025: Innovation for Tomorrow',
                'content' => '<p>Mark your calendars! The <strong>CCS Annual Tech Summit 2025</strong> is happening on <strong>March 15-17, 2025</strong> at the Gordon College Auditorium.</p>
                
                <h3>Event Highlights</h3>
                <ul>
                    <li><strong>Keynote Speakers:</strong> Industry leaders from top tech companies</li>
                    <li><strong>Workshops:</strong> Hands-on sessions on AI, Cloud Computing, and Cybersecurity</li>
                    <li><strong>Hackathon:</strong> 24-hour coding competition with exciting prizes</li>
                    <li><strong>Career Fair:</strong> Connect with potential employers</li>
                    <li><strong>Project Exhibition:</strong> Showcase of student innovations</li>
                </ul>
                
                <h3>Registration</h3>
                <p>Early bird registration is now open! Get 20% off by registering before February 28, 2025.</p>
                
                <p>This year\'s theme, "<em>Innovation for Tomorrow</em>", focuses on how technology can address current global challenges and create a sustainable future.</p>
                
                <p>Don\'t miss this opportunity to learn, network, and grow!</p>',
                'thumbnail' => null,
            ],
            [
                'title' => 'CCS Students Ace AWS Certified Cloud Practitioner Exam',
                'content' => '<p>We are proud to announce that <strong>15 CCS students</strong> have successfully passed the <strong>AWS Certified Cloud Practitioner</strong> examination, achieving a 100% pass rate for the batch!</p>
                
                <p>This certification validates their understanding of AWS Cloud concepts, services, security, architecture, pricing, and support. It\'s a significant achievement that enhances their employability in the competitive IT industry.</p>
                
                <h3>Certified Students</h3>
                <p>Congratulations to all the newly certified AWS Cloud Practitioners from BSIT 3A and 3B! Your dedication to continuous learning is an inspiration to all.</p>
                
                <h3>Upcoming Certification Programs</h3>
                <p>The CCS department, in partnership with AWS Academy, will be offering more certification preparation courses:</p>
                <ul>
                    <li>AWS Solutions Architect Associate - Starting April 2025</li>
                    <li>AWS Developer Associate - Starting May 2025</li>
                </ul>
                
                <p>Interested students can register at the CCS office or through the student portal.</p>',
                'thumbnail' => null,
            ],
            [
                'title' => 'Phoenix Coding Club Welcomes New Members',
                'content' => '<p>The <strong>Phoenix Coding Club</strong> is now accepting applications for new members for the second semester of AY 2025-2026!</p>
                
                <p>Whether you\'re a beginner looking to learn programming or an experienced coder wanting to sharpen your skills, the Phoenix Coding Club welcomes you.</p>
                
                <h3>What We Offer</h3>
                <ul>
                    <li>Weekly coding sessions and tutorials</li>
                    <li>Competitive programming training</li>
                    <li>Project collaboration opportunities</li>
                    <li>Industry mentorship programs</li>
                    <li>Participation in national and international coding competitions</li>
                </ul>
                
                <h3>How to Join</h3>
                <p>Submit your application through the Google Form link posted on our Facebook page or visit us at the CCS Laboratory during club hours (MWF, 4:00 PM - 6:00 PM).</p>
                
                <p>Join us and become part of a community passionate about coding and technology!</p>',
                'thumbnail' => null,
            ],
            [
                'title' => 'Research Paper Accepted at International Conference',
                'content' => '<p>A research paper co-authored by CCS faculty and students has been accepted for presentation at the <strong>International Conference on Computer Science and Artificial Intelligence (ICCSAI 2025)</strong> in Singapore!</p>
                
                <h3>About the Research</h3>
                <p>The paper titled "<em>Enhancing Sentiment Analysis Using Hybrid Deep Learning Models for Filipino Text</em>" presents a novel approach to natural language processing for low-resource languages.</p>
                
                <h3>Research Team</h3>
                <ul>
                    <li><strong>Dr. Maria Teresa Cruz</strong> - Principal Investigator</li>
                    <li><strong>Engr. Roberto Santos</strong> - Co-Investigator</li>
                    <li><strong>Anna Marie Garcia (BSCS 4)</strong> - Student Researcher</li>
                    <li><strong>Michael John Reyes (BSCS 4)</strong> - Student Researcher</li>
                </ul>
                
                <p>This achievement highlights the college\'s commitment to research excellence and the development of solutions relevant to our local context.</p>
                
                <p>Congratulations to the entire research team!</p>',
                'thumbnail' => null,
            ],
        ];

        foreach ($posts as $index => $postData) {
            Post::create([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'thumbnail' => $postData['thumbnail'],
                'organization_user_id' => $author->id,
                'created_at' => now()->subDays(count($posts) - $index), // Stagger creation dates
                'updated_at' => now()->subDays(count($posts) - $index),
            ]);
        }
    }
}
