MASTER ARCHITECTURE PROMPT
Prompt: Generate Full System Architecture
You are a senior full-stack software architect.

Help me design a scalable online training platform called "Data Tutors".

The platform will offer the following courses:
1. Excel (Beginner to Advanced)
2. Data Analysis (Excel, SQL, Python Intro, Power BI)
3. Data Automation (Zapier, Make.com, Airtable, N8N)

Requirements:

- Users can register and log in
- Users can enroll in courses
- Courses are broken into modules and lessons
- Users can resume lessons from where they stopped
- Lessons are text-based with embedded YouTube videos
- Each lesson ends with quizzes or short tests
- There should be a Q&A forum per course
- Admin dashboard to:
    - Add/edit/delete courses
    - Add modules and lessons
    - Add quizzes
    - Manage users
    - Moderate forum

Generate:
1. Recommended tech stack (frontend + backend + database)
2. Database schema
3. Authentication flow
4. Course progress tracking logic
5. Forum architecture
6. Admin control structure
7. Hosting recommendation

2️⃣ LANDING PAGE PROMPT
Prompt: Generate Landing Page UI
Create a modern, responsive landing page for an online academy called "Data Tutors".

Courses offered:
- Excel (Beginner to Advanced)
- Data Analysis (Excel, SQL, Python Intro, Power BI)
- Data Automation (Zapier, Make, Airtable, N8N)

Landing page should include:

1. Hero section with:
   - Catchy headline
   - Short value proposition
   - CTA buttons (Browse Courses, Get Started)

2. Featured Courses section (3 cards)
3. Why Choose Us section
4. How It Works (Enroll → Learn → Practice → Earn Certificate)
5. Testimonials
6. Pricing / Enrollment CTA
7. Footer with links

Style:
- Modern
- Professional
- Tech-focused
- Clean UI

Generate:
- HTML
- CSS
- Responsive layout
- Dummy images placeholders

3️⃣ COURSES PAGE PROMPT
Prompt: Generate Courses Listing Page
Create a Courses page for an online learning platform.

Requirements:
- Grid layout of course cards
- Each card shows:
    - Course title
    - Short description
    - Level
    - Enroll button
    - View details button
    - Course price
- Filter by category
- sort option
- Search bar
- Pagination

Courses:
- Excel
- Data Analysis
- Data Automation

Each course card should link to a course details page.

4️⃣ COURSE DETAILS PAGE PROMPT
Prompt: Generate Course Details Page
Create a Course Details page template.

Each course should include:

1. Course title
2. Overview
3. Curriculum (expandable sections)
   - Modules
   - Lessons inside modules
4. Each lesson should be clickable
5. Enroll button
6. Resume course button (if already enrolled)
7. Course duration
8. Instructor section
9. Q&A section preview

Lessons should:
- Open a lesson page
- Allow users to continue from where they stopped

Generate:
- Clean layout
- Sidebar curriculum navigation
- Progress bar

5️⃣ LESSON PAGE PROMPT
Prompt: Generate Lesson Page with Progress Tracking
Create a lesson page for an online course.

Each lesson should include:

1. Lesson title
2. Text-based learning content
3. Embedded YouTube video
4. Downloadable resources (if any)
5. Mark as complete button
6. Progress tracking system
7. Next lesson button
8. Previous lesson button

At the end of each lesson:
- Include short quiz (MCQs)
- Store quiz results in database
- Track completion status

Generate:
- Frontend layout
- Backend logic for saving progress
- Resume-from-last-lesson logic

6️⃣ QUIZ SYSTEM PROMPT
Prompt: Generate Quiz System
Build a quiz system for an online learning platform.

Requirements:
- Multiple choice questions
- 4 options per question
- Immediate feedback
- Store user score
- Pass/fail logic
- Allow retake
- Admin can add/edit quiz questions

Database tables needed:
- quizzes
- quiz_questions
- quiz_options
- quiz_results

Generate:
- Backend API endpoints
- Frontend quiz UI
- Score calculation logic

7️⃣ USER AUTHENTICATION PROMPT
Prompt: Build Authentication System
Create a complete user authentication system.

Requirements:
- Registration
- Login
- Password reset
- Email verification
- Role-based access (Admin, Student)
- JWT or session-based authentication

User Dashboard:
- My Courses
- Resume Learning
- Quiz Results
- Profile Settings

Include:
- Secure password hashing
- Protected routes
- Enrollment tracking

8️⃣ USER DASHBOARD PROMPT
Prompt: Generate Student Dashboard
Create a student dashboard.

Features:
- List enrolled courses
- Show progress % for each course
- Resume button
- Quiz scores
- Certificates earned
- Forum activity
- Profile settings

UI should:
- Be clean
- Show progress bars
- Allow quick navigation to lessons

9️⃣ Q&A FORUM PROMPT
Prompt: Build Course Q&A Forum
Create a Q&A forum system for each course.

Features:
- Students can post questions
- Students can comment
- Admin can reply as instructor
- Upvote system
- Mark answer as accepted
- Filter by:
    - Most recent
    - Most helpful
- Admin moderation panel

Database tables:
- forum_questions
- forum_answers
- forum_votes

Generate:
- Backend API
- Frontend forum UI
- Moderation tools

🔟 ADMIN PANEL PROMPT
Prompt: Generate Admin Dashboard
Create a full admin dashboard for the Data Tutors platform.

Admin capabilities:

Course Management:
- Add new course
- Edit course
- Delete course
- Add modules
- Add lessons
- Upload lesson content
- Add quizzes

User Management:
- View users
- Block users
- Assign roles

Forum Moderation:
- Delete inappropriate questions
- Respond as instructor

Analytics:
- Total users
- Active users
- Course enrollments
- Quiz performance

Generate:
- Admin layout
- Secure access control
- CRUD functionality

1️⃣1️⃣ PROGRESS TRACKING PROMPT
Prompt: Implement Learning Progress Engine
Create a progress tracking engine for online learning.

Requirements:
- Track lesson completion
- Track quiz completion
- Track overall course completion %
- Resume from last visited lesson
- Store timestamps

Database tables:
- user_course_progress
- user_lesson_progress

Generate:
- Backend logic
- Database schema
- API endpoints

1️⃣2️⃣ CERTIFICATE SYSTEM PROMPT
Prompt: Generate Certificate System
Create a certificate generation system.

Requirements:
- Certificate issued after:
    - All lessons completed
    - Final quiz passed
- Generate downloadable PDF certificate
- Include:
    - Student name
    - Course name
    - Date
    - Unique certificate ID
- Admin can verify certificate ID

Generate:
- Backend certificate generation logic
- PDF generation code
- Certificate verification endpoint

1️⃣3️⃣ PAYMENT SYSTEM PROMPT (Optional)
Prompt: Integrate Payment Gateway
Integrate payment system for course enrollment.

Requirements:
- One-time payment per course
- Payment confirmation
- Automatic enrollment after payment
- Payment history tracking
- Admin revenue dashboard

Suggested gateways:
- Paystack
- Stripe

Generate:
- Backend integration
- Secure webhook handling
- Enrollment automation

1️⃣4️⃣ DEPLOYMENT PROMPT
Prompt: Prepare for Deployment
Prepare this online training platform for deployment.

Requirements:
- Environment variables
- Production database setup
- Secure hosting
- HTTPS setup
- File storage solution
- Performance optimization
- Basic security hardening

Generate:
- Deployment checklist
- Recommended hosting providers
- CI/CD structure

📌 BONUS: CONTENT STRUCTURE PROMPT
Prompt: Generate Course Content Structure
Generate a complete curriculum outline for:

1. Excel (Beginner to Advanced)
2. Data Analysis (Excel, SQL, Python, Power BI)
3. Data Automation (Zapier, Make.com, Airtable, N8N)

Break down into:
- Modules
- Lessons
- Mini projects
- Final projects
- Quizzes per module