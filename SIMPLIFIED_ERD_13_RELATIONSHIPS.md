# SIMPLIFIED PHYSICAL ERD - 13 Entities, 13 Relationships
## Digital Make-Up Class Request System (DMCRS)

---

## OVERVIEW

This is a **simplified Physical ERD** with **13 entities = 13 relationships** (one primary relationship per entity).

---

## EXPLANATION

The Physical Entity-Relationship Diagram (ERD) represents the actual implementation of the database design with detailed information about table structures, fields, data types, and constraints. It transforms the abstract logical design into a concrete blueprint that could be directly used to build the database in a relational database management system (RDBMS). In this physical ERD, each entity is translated into a table with specific fields that include primary keys, foreign keys, and appropriate data types such as `INT`, `VARCHAR`, `ENUM`, and `TIMESTAMP`. Constraints like `NOT NULL`, `UNIQUE`, `DEFAULT`, and `AUTO_INCREMENT` are explicitly defined to ensure data integrity and enforce business rules. Relationships are implemented using foreign key constraints that maintain referential integrity between related tables, providing a complete and accurate foundation for the database system to operate effectively.

---

## DATA DICTIONARY

### Table 1. Departments

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Department_ID | INT, AUTO_INCREMENT | Unique ID for each department | Primary Key |
| Department_Name | VARCHAR(100) | Name of the department | Not Null |
| Created_At | TIMESTAMP | When the record was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 2. Users

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Users_ID | INT, AUTO_INCREMENT | Unique ID for each user | Primary Key |
| Department_ID | INT | Department where user belongs | Foreign Key (references Departments), Not Null |
| Name | VARCHAR(100) | Full name of the user | Not Null |
| Email | VARCHAR(100) | Email address | Not Null, Unique |
| Email_Verified | BOOLEAN | Whether email is verified | Default: FALSE |
| Password | VARCHAR(255) | Encrypted password | Not Null |
| Role | ENUM('faculty', 'admin', 'department_chair', 'academic_head') | User role in the system | Not Null |
| Is_Active | BOOLEAN | Whether account is active | Default: TRUE |
| Contact_Number | VARCHAR(20) | User's contact number | NULLABLE |
| Status | ENUM('Pending', 'Approved', 'Rejected') | Account status | NULLABLE |
| Profile_Image | VARCHAR(255) | Path to profile image | NULLABLE |
| Created_At | TIMESTAMP | When the account was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |
| Bio | TEXT | User biography | NULLABLE |

### Table 3. Students

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Student_ID | INT, AUTO_INCREMENT | Unique ID for each student | Primary Key |
| Department_ID | INT | Department where student belongs | Foreign Key (references Departments), Not Null |
| Section_ID | INT | Section where student is enrolled | Foreign Key (references Sections), NULLABLE |
| Student_ID_Number | VARCHAR(50) | Student identification number | Not Null, Unique |
| First_Name | VARCHAR(100) | Student's first name | Not Null |
| Middle_Name | VARCHAR(100) | Student's middle name | NULLABLE |
| Last_Name | VARCHAR(100) | Student's last name | Not Null |
| Email | VARCHAR(100) | Email address of the student | Not Null, Unique |
| Contact_Number | VARCHAR(20) | Student's contact number | NULLABLE |
| Year_Level | INT | Year level of the student | Not Null |
| Status | VARCHAR(50) | Student status (e.g. enrolled, inactive) | NULLABLE |
| Created_At | TIMESTAMP | When the record was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 4. Sections

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Section_ID | INT, AUTO_INCREMENT | Unique ID for each section | Primary Key |
| Department_ID | INT | Department that owns the section | Foreign Key (references Departments), Not Null |
| Section_Name | VARCHAR(100) | Name of the section | Not Null |
| Year_Level | INT | Year level of the section | Not Null |
| Created_At | TIMESTAMP | When the record was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 5. Subjects

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Subject_ID | INT, AUTO_INCREMENT | Unique ID for each subject | Primary Key |
| Department_ID | INT | Department that offers the subject | Foreign Key (references Departments), Not Null |
| Subject_Code | VARCHAR(50) | Code of the subject | Not Null |
| Subject_Title | VARCHAR(200) | Title of the subject | Not Null |
| Description | TEXT | Subject description | NULLABLE |
| Created_At | TIMESTAMP | When the record was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 6. MakeUpClassRequest

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Request_ID | INT, AUTO_INCREMENT | Unique ID for each make-up class request | Primary Key |
| User_ID | INT | The faculty who submitted the request | Foreign Key (references Users), Not Null |
| Subject_ID | INT | Subject that the class is for | Foreign Key (references Subjects), Not Null |
| Department_ID | INT | Department of the request | Foreign Key (references Departments), NULLABLE |
| Sections_ID | INT | Section for the make-up class | Foreign Key (references Sections), NULLABLE |
| Section | VARCHAR(100) | Section name (denormalized) | NULLABLE |
| Semester | VARCHAR(50) | Semester of the request | NULLABLE |
| Subject | VARCHAR(100) | Subject name (denormalized) | NULLABLE |
| Subject_Title | VARCHAR(200) | Subject title (denormalized) | NULLABLE |
| Room | VARCHAR(100) | Preferred room (denormalized) | NULLABLE |
| Reason | TEXT | Reason for make-up class | Not Null |
| Preferred_Date | DATE | Preferred date of the make-up class | Not Null |
| Preferred_Time_Start | TIME | Preferred start time for the class | Not Null |
| Preferred_Time_End | TIME | Preferred end time for the class | NULLABLE |
| Status | ENUM('Pending', 'Approved', 'Rejected') | Status of the request | Default: 'Pending' |
| Submitted_To | VARCHAR(100) | Person/position submitted to | NULLABLE |
| Attachment | VARCHAR(255) | File attachment, if any | NULLABLE |
| Student_List | TEXT | List of students for the make-up class | NULLABLE |
| Proof_of_Conduct | VARCHAR(255) | Proof of conduct document | NULLABLE |
| Tracking_Number | VARCHAR(100) | Tracking number for the request | NULLABLE |
| Created_At | TIMESTAMP | When the request was submitted | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |
| Chair_Remarks | TEXT | Remarks from department chair | NULLABLE |
| Head_Remarks | TEXT | Remarks from academic head | NULLABLE |

### Table 7. Confirmation

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Confirmation_ID | INT, AUTO_INCREMENT | Unique confirmation record ID | Primary Key |
| Request_ID | INT | ID of the approved request | Foreign Key (references MakeUpClassRequest), Not Null |
| Student_ID | INT | ID of the student confirming attendance | Foreign Key (references Students), NULLABLE |
| Student_Email | VARCHAR(100) | Email of the student (if not registered) | NULLABLE |
| Student_ID_Number | VARCHAR(50) | Student ID number (if not registered) | NULLABLE |
| Student_Name | VARCHAR(200) | Student name (if not registered) | NULLABLE |
| Status | ENUM('confirmed', 'declined') | Confirmation status | Not Null |
| Reason | TEXT | Optional reason if declined | NULLABLE |
| Attended | BOOLEAN | Whether student attended the class | NULLABLE |
| Confirmation | BOOLEAN | Confirmation flag | NULLABLE |
| Created_At | TIMESTAMP | Date of confirmation | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 8. Approval

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Approval_ID | INT, AUTO_INCREMENT | Unique ID for approval | Primary Key |
| Request_ID | INT | ID of the make-up class request | Foreign Key (references MakeUpClassRequest), Not Null |
| Approved_By | INT | Approver's user ID | Foreign Key (references Users), Not Null |
| Position | ENUM('Chair', 'Academic Head') | Role of approver | Not Null |
| Remarks | TEXT | Comments/notes from the approver | NULLABLE |
| Is_Final | BOOLEAN | Marks if it's the final decision | Default: FALSE |
| Status | ENUM('Pending', 'Approved', 'Rejected') | Approval status | Not Null |
| Decision | VARCHAR(100) | Decision made | NULLABLE |
| Created_At | TIMESTAMP | Time of approval/rejection | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 9. Schedule

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Schedule_ID | INT, AUTO_INCREMENT | Unique ID for schedule | Primary Key |
| Request_ID | INT | Associated make-up class request ID | Foreign Key (references MakeUpClassRequest), NULLABLE |
| Department_ID | INT | Department of the schedule | Foreign Key (references Departments), NULLABLE |
| Faculty_Loading_Detail_ID | INT | Link to faculty loading detail | Foreign Key (references FacultyLoadingDetail), NULLABLE |
| User_ID | INT | Faculty assigned to the schedule | Foreign Key (references Users), NULLABLE |
| Subject_Code | VARCHAR(50) | Subject code (denormalized) | NULLABLE |
| Subject_Title | VARCHAR(200) | Subject title (denormalized) | NULLABLE |
| Section | VARCHAR(100) | Section name (denormalized) | NULLABLE |
| Room | INT | Assigned room for the class | Foreign Key (references Room), NULLABLE |
| Semester | VARCHAR(50) | Semester of the schedule | NULLABLE |
| Time_Start | TIME | Start time of the class | Not Null |
| Time_End | TIME | End time of the class | Not Null |
| Day_of_Week | VARCHAR(20) | Day of the week | NULLABLE |
| Type | ENUM('Regular', 'MakeUp') | Type of schedule | Not Null |
| Status | ENUM('Scheduled', 'Completed', 'Cancelled') | Status of the schedule | Default: 'Scheduled' |
| School_Year | VARCHAR(50) | School year | NULLABLE |
| Created_At | TIMESTAMP | When the schedule was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 10. Room

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Room_ID | INT, AUTO_INCREMENT | Unique ID for each room | Primary Key |
| Room_Name | VARCHAR(100) | Room label/name | Not Null |
| Location | VARCHAR(100) | Building or room location | Not Null |
| Capacity | INT | Maximum number of students | Not Null |
| Created_At | TIMESTAMP | When the record was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 11. Notification

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Notification_ID | INT, AUTO_INCREMENT | Unique ID for each notification | Primary Key |
| Type | VARCHAR(100) | Type of notification | Not Null |
| Notifiable_Type | VARCHAR(100) | Type of entity being notified (polymorphic) | Not Null |
| Notifiable_ID | INT | ID of the entity being notified (polymorphic FK) | Not Null |
| Data | TEXT | Notification data/content | NULLABLE |
| Read_At | TIMESTAMP | When notification was read | NULLABLE |
| Created_At | TIMESTAMP | Time the notification was sent | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 12. FacultyLoadingHeader

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Faculty_Loading_Header_ID | INT, AUTO_INCREMENT | Unique ID for faculty loading header | Primary Key |
| Department_ID | INT | Department that owns the loading | Foreign Key (references Departments), Not Null |
| Uploaded_By | INT | User who uploaded the loading | Foreign Key (references Users), Not Null |
| Semester | ENUM('1st', '2nd', 'summer') | Semester of the loading | Not Null |
| School_Year | VARCHAR(50) | School year of the loading | Not Null |
| Status | VARCHAR(50) | Status of the loading (Draft/Active/Archived) | NULLABLE |
| Remarks | TEXT | Additional notes | NULLABLE |
| Created_At | TIMESTAMP | When the loading was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

### Table 13. FacultyLoadingDetail

| Field Name | Data Type | Description | Constraints |
|------------|-----------|-------------|-------------|
| Faculty_Loading_Detail_ID | INT, AUTO_INCREMENT | Unique ID for faculty loading detail | Primary Key |
| Faculty_Loading_Header_ID | INT | Header ID this detail belongs to | Foreign Key (references FacultyLoadingHeader), Not Null |
| Instructor_ID | INT | Faculty member assigned | Foreign Key (references Users), Not Null |
| Subject_Code | VARCHAR(50) | Subject code for the class | NULLABLE |
| Section | VARCHAR(100) | Section for the class | NULLABLE |
| Room | VARCHAR(100) | Room assigned (denormalized) | NULLABLE |
| Day_of_Week | VARCHAR(20) | Day of the week | NULLABLE |
| Time_Start | TIME | Start time of the class | NULLABLE |
| Time_End | TIME | End time of the class | NULLABLE |
| Units | INT | Number of units | NULLABLE |
| Created_At | TIMESTAMP | When the record was created | Default: CURRENT_TIMESTAMP |
| Updated_At | TIMESTAMP | Last update time | Default: CURRENT_TIMESTAMP ON UPDATE |

---

## STEP 1: LIST ALL ENTITIES (13 Total)

1. **Departments** - Department information
2. **Users** - System users (Faculty, Admin, Department Chair, Academic Head)
3. **Students** - Student information
4. **Subjects** - Subject information
5. **Sections** - Section information
6. **MakeUpClassRequest** - Make-up class requests
7. **Confirmation** - Student confirmations
8. **Approval** - Approval decisions
9. **Schedule** - Make-up class schedules
10. **Room** - Room information
11. **Notification** - Email notifications
12. **FacultyLoadingHeader** - Faculty loading header (semester, school year)
13. **FacultyLoadingDetail** - Faculty loading details (classes assigned)

---

## STEP 2: ENTITY ATTRIBUTES (Physical Database Structure)

### 1. DEPARTMENTS
**PK:** `Department_ID` (INT, AUTO_INCREMENT)
**Attributes:**
- `Department_ID` (PK, INT, AUTO_INCREMENT)
- `Department_Name` (VARCHAR, NOT NULL)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 2. USERS
**PK:** `Users_ID` (INT, AUTO_INCREMENT)
**FK:** `Department_ID` → Departments.Department_ID
**Attributes:**
- `Users_ID` (PK, INT, AUTO_INCREMENT)
- `Department_ID` (FK, INT, references Departments)
- `Name` (VARCHAR, NOT NULL)
- `Email` (VARCHAR, NOT NULL, UNIQUE)
- `Email_Verified` (BOOLEAN)
- `Password` (VARCHAR, NOT NULL)
- `Role` (ENUM: 'faculty', 'admin', 'department_chair', 'academic_head', NOT NULL)
- `Is_Active` (BOOLEAN, DEFAULT TRUE)
- `Contact_Number` (VARCHAR, NULLABLE)
- `Status` (ENUM: 'Pending', 'Approved', 'Rejected', NULLABLE)
- `Profile_Image` (VARCHAR, NULLABLE)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)
- `Bio` (TEXT, NULLABLE)

### 3. STUDENTS
**PK:** `Student_ID` (INT, AUTO_INCREMENT)
**FK:** `Department_ID` → Departments.Department_ID
**FK:** `Section_ID` → Sections.Section_ID
**Attributes:**
- `Student_ID` (PK, INT, AUTO_INCREMENT)
- `Department_ID` (FK, INT, references Departments)
- `Section_ID` (FK, INT, references Sections, NULLABLE)
- `Student_ID_Number` (VARCHAR, NOT NULL, UNIQUE)
- `First_Name` (VARCHAR, NOT NULL)
- `Middle_Name` (VARCHAR, NULLABLE)
- `Last_Name` (VARCHAR, NOT NULL)
- `Email` (VARCHAR, NOT NULL, UNIQUE)
- `Contact_Number` (VARCHAR, NULLABLE)
- `Year_Level` (INT, NOT NULL)
- `Status` (VARCHAR, NULLABLE)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 4. SECTIONS
**PK:** `Section_ID` (INT, AUTO_INCREMENT)
**FK:** `Department_ID` → Departments.Department_ID
**Attributes:**
- `Section_ID` (PK, INT, AUTO_INCREMENT)
- `Department_ID` (FK, INT, references Departments)
- `Section_Name` (VARCHAR, NOT NULL)
- `Year_Level` (INT, NOT NULL)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 5. SUBJECTS
**PK:** `Subject_ID` (INT, AUTO_INCREMENT)
**FK:** `Department_ID` → Departments.Department_ID
**Attributes:**
- `Subject_ID` (PK, INT, AUTO_INCREMENT)
- `Department_ID` (FK, INT, references Departments)
- `Subject_Code` (VARCHAR, NOT NULL)
- `Subject_Title` (VARCHAR, NOT NULL)
- `Description` (TEXT, NULLABLE)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 6. MAKEUPCLASSREQUEST
**PK:** `Request_ID` (INT, AUTO_INCREMENT)
**FK:** `User_ID` → Users.Users_ID
**FK:** `Subject_ID` → Subjects.Subject_ID
**FK:** `Department_ID` → Departments.Department_ID (implied)
**FK:** `Sections_ID` → Sections.Section_ID (implied)
**Attributes:**
- `Request_ID` (PK, INT, AUTO_INCREMENT)
- `User_ID` (FK, INT, references Users)
- `Subject_ID` (FK, INT, references Subjects)
- `Department_ID` (FK, INT, references Departments, NULLABLE)
- `Sections_ID` (FK, INT, references Sections, NULLABLE)
- `Section` (VARCHAR, NULLABLE)
- `Semester` (VARCHAR, NULLABLE)
- `Subject` (VARCHAR, NULLABLE)
- `Subject_Title` (VARCHAR, NULLABLE)
- `Room` (VARCHAR, NULLABLE)
- `Reason` (TEXT, NOT NULL)
- `Preferred_Date` (DATE, NOT NULL)
- `Preferred_Time_Start` (TIME, NOT NULL)
- `Preferred_Time_End` (TIME, NULLABLE)
- `Status` (ENUM: 'Pending', 'Approved', 'Rejected', DEFAULT 'Pending')
- `Submitted_To` (VARCHAR, NULLABLE)
- `Attachment` (VARCHAR, NULLABLE)
- `Student_List` (TEXT, NULLABLE)
- `Proof_of_Conduct` (VARCHAR, NULLABLE)
- `Tracking_Number` (VARCHAR, NULLABLE)
- `Created_At` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- `Updated_At` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE)
- `Chair_Remarks` (TEXT, NULLABLE)
- `Head_Remarks` (TEXT, NULLABLE)

### 7. CONFIRMATION
**PK:** `Confirmation_ID` (INT, AUTO_INCREMENT)
**FK:** `Request_ID` → MakeUpClassRequest.Request_ID
**FK:** `Student_ID` → Students.Student_ID (implied)
**Attributes:**
- `Confirmation_ID` (PK, INT, AUTO_INCREMENT)
- `Request_ID` (FK, INT, references MakeUpClassRequest)
- `Student_ID` (FK, INT, references Students, NULLABLE)
- `Student_Email` (VARCHAR, NULLABLE)
- `Student_ID_Number` (VARCHAR, NULLABLE)
- `Student_Name` (VARCHAR, NULLABLE)
- `Status` (ENUM: 'confirmed', 'declined', NOT NULL)
- `Reason` (TEXT, NULLABLE)
- `Attended` (BOOLEAN, NULLABLE)
- `Confirmation` (BOOLEAN, NULLABLE)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 8. APPROVAL
**PK:** `Approval_ID` (INT, AUTO_INCREMENT)
**FK:** `Request_ID` → MakeUpClassRequest.Request_ID
**FK:** `Approved_By` → Users.Users_ID
**Attributes:**
- `Approval_ID` (PK, INT, AUTO_INCREMENT)
- `Request_ID` (FK, INT, references MakeUpClassRequest)
- `Approved_By` (FK, INT, references Users)
- `Position` (ENUM: 'Chair', 'Academic Head', NOT NULL)
- `Remarks` (TEXT, NULLABLE)
- `Is_Final` (BOOLEAN, DEFAULT FALSE)
- `Status` (ENUM: 'Pending', 'Approved', 'Rejected', NOT NULL)
- `Decision` (VARCHAR, NULLABLE)
- `Created_At` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- `Updated_At` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE)

### 9. SCHEDULE
**PK:** `Schedule_ID` (INT, AUTO_INCREMENT)
**FK:** `Request_ID` → MakeUpClassRequest.Request_ID
**FK:** `Room` → Room.Room_ID (implied)
**FK:** `Department_ID` → Departments.Department_ID (implied)
**FK:** `Faculty_Loading_Detail_ID` → FacultyLoadingDetail.Faculty_Loading_Detail_ID (implied)
**FK:** `User_ID` → Users.Users_ID (implied)
**Attributes:**
- `Schedule_ID` (PK, INT, AUTO_INCREMENT)
- `Request_ID` (FK, INT, references MakeUpClassRequest)
- `Department_ID` (FK, INT, references Departments, NULLABLE)
- `Faculty_Loading_Detail_ID` (FK, INT, references FacultyLoadingDetail, NULLABLE)
- `User_ID` (FK, INT, references Users, NULLABLE)
- `Subject_Code` (VARCHAR, NULLABLE)
- `Subject_Title` (VARCHAR, NULLABLE)
- `Section` (VARCHAR, NULLABLE)
- `Room` (FK, INT, references Room, NULLABLE)
- `Semester` (VARCHAR, NULLABLE)
- `Time_Start` (TIME, NOT NULL)
- `Time_End` (TIME, NOT NULL)
- `Day_of_Week` (VARCHAR, NULLABLE)
- `Type` (ENUM: 'Regular', 'MakeUp', NOT NULL)
- `Status` (ENUM: 'Scheduled', 'Completed', 'Cancelled', DEFAULT 'Scheduled')
- `School_Year` (VARCHAR, NULLABLE)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 10. ROOM
**PK:** `Room_ID` (INT, AUTO_INCREMENT)
**Attributes:**
- `Room_ID` (PK, INT, AUTO_INCREMENT)
- `Room_Name` (VARCHAR, NOT NULL)
- `Location` (VARCHAR, NOT NULL)
- `Capacity` (INT, NOT NULL)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 11. NOTIFICATION
**PK:** `Notification_ID` (INT, AUTO_INCREMENT)
**Attributes (Polymorphic Association):**
- `Notification_ID` (PK, INT, AUTO_INCREMENT)
- `Type` (VARCHAR, NOT NULL)
- `Notifiable_Type` (VARCHAR, NOT NULL) - Polymorphic: 'Student', 'User', 'Request'
- `Notifiable_ID` (INT, NOT NULL) - Polymorphic FK
- `Data` (TEXT, NULLABLE)
- `Read_At` (TIMESTAMP, NULLABLE)
- `Created_At` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- `Updated_At` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE)

### 12. FACULTYLOADINGHEADER
**PK:** `Faculty_Loading_Header_ID` (INT, AUTO_INCREMENT)
**FK:** `Department_ID` → Departments.Department_ID
**FK:** `Uploaded_By` → Users.Users_ID (implied)
**Attributes:**
- `Faculty_Loading_Header_ID` (PK, INT, AUTO_INCREMENT)
- `Department_ID` (FK, INT, references Departments)
- `Uploaded_By` (FK, INT, references Users)
- `Semester` (ENUM: '1st', '2nd', 'summer', NOT NULL)
- `School_Year` (VARCHAR, NOT NULL)
- `Status` (VARCHAR, NULLABLE)
- `Remarks` (TEXT, NULLABLE)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

### 13. FACULTYLOADINGDETAIL
**PK:** `Faculty_Loading_Detail_ID` (INT, AUTO_INCREMENT)
**FK:** `Faculty_Loading_Header_ID` → FacultyLoadingHeader.Faculty_Loading_Header_ID
**FK:** `Instructor_ID` → Users.Users_ID (implied)
**Attributes:**
- `Faculty_Loading_Detail_ID` (PK, INT, AUTO_INCREMENT)
- `Faculty_Loading_Header_ID` (FK, INT, references FacultyLoadingHeader)
- `Instructor_ID` (FK, INT, references Users)
- `Subject_Code` (VARCHAR, NULLABLE)
- `Section` (VARCHAR, NULLABLE)
- `Room` (VARCHAR, NULLABLE)
- `Day_of_Week` (VARCHAR, NULLABLE)
- `Time_Start` (TIME, NULLABLE)
- `Time_End` (TIME, NULLABLE)
- `Units` (INT, NULLABLE)
- `Created_At` (TIMESTAMP)
- `Updated_At` (TIMESTAMP)

---

## STEP 3: ENTITY PLACEMENT (Layout Based on Actual Connections)

**Layout Strategy:** MakeUpClassRequest is the HUB - it connects to 5 entities (Users, Subjects, Confirmation, Approval, Schedule)

**Optimized Layout:**

```
                    [DEPARTMENTS]
                    (Top)
                    ╱     ╲
                   ╱       ╲
            [USERS]    [FACULTYLOADINGHEADER]
            (Left)     (Right)
              │          │
         [SUBJECTS]  [FACULTYLOADINGDETAIL]
         (Left)      (Right)
              │          │
              └────┬─────┘
                   │
         [MAKEUPCLASSREQUEST]
              (Center HUB)
              ╱  │  ╲
             ╱   │   ╲
    [CONFIRMATION]│ [APPROVAL]
        (Left)    │  (Right)
          ↑       │     ↑
          │       │     │
      [STUDENTS]  │     │
        (Left)    │     │
          │       │     │
      [SECTIONS]  │     │
        (Left)    │     │
          │       │     │
    [NOTIFICATION]│     │
        (Left)    │     │
          │       │     │
          └───────┼─────┘
                  │
             [SCHEDULE]
             (Bottom)
                  ↑
              [ROOM]
             (Bottom Right)
```

**Position Guide:**
- **Top:** Departments (connects to Users, FacultyLoadingHeader)
- **Left Side:** Users → Subjects → MakeUpClassRequest → Confirmation → Students → Sections → Notification
- **Right Side:** FacultyLoadingHeader → FacultyLoadingDetail, Approval
- **Center:** MakeUpClassRequest (HUB - connects to 5 entities)
- **Bottom:** Schedule (connects to MakeUpClassRequest, Room), Room

**See `ERD_FINAL_LAYOUT.md` for detailed step-by-step drawing instructions based on your actual 13 relationships.**

---

## STEP 4: 13 ESSENTIAL RELATIONSHIPS (One per Entity)

**Following Old ERD Pattern: 13 Entities = 13 Relationships**

### CORE RELATIONSHIPS (8):

1. **USERS → MAKEUPCLASSREQUEST**
   - **Label:** "creates"
   - **Cardinality:** 1 User to Many MakeUpClassRequest (1:N)
   - **FK:** MakeUpClassRequest.User_ID → Users.Users_ID

2. **MAKEUPCLASSREQUEST → CONFIRMATION**
   - **Label:** "has"
   - **Cardinality:** 1 MakeUpClassRequest to Many Confirmation (1:N)
   - **FK:** Confirmation.Request_ID → MakeUpClassRequest.Request_ID

3. **STUDENTS → CONFIRMATION**
   - **Label:** "responds with"
   - **Cardinality:** 1 Student to Many Confirmation (1:N)
   - **FK:** Confirmation.Student_ID → Students.Student_ID (nullable, can also use Student_Email)

4. **MAKEUPCLASSREQUEST → APPROVAL**
   - **Label:** "requires"
   - **Cardinality:** 1 MakeUpClassRequest to Many Approval (1:N)
   - **FK:** Approval.Request_ID → MakeUpClassRequest.Request_ID

5. **USERS → APPROVAL**
   - **Label:** "approves"
   - **Cardinality:** 1 User to Many Approval (1:N)
   - **FK:** Approval.Approved_By → Users.Users_ID

6. **MAKEUPCLASSREQUEST → SCHEDULE**
   - **Label:** "creates"
   - **Cardinality:** 1 MakeUpClassRequest to Many Schedule (1:N)
   - **FK:** Schedule.Request_ID → MakeUpClassRequest.Request_ID

7. **ROOM → SCHEDULE**
   - **Label:** "used for"
   - **Cardinality:** 1 Room to Many Schedule (1:N)
   - **FK:** Schedule.Room → Room.Room_ID

8. **STUDENTS → NOTIFICATION**
   - **Label:** "receives"
   - **Cardinality:** 1 Student to Many Notification (1:N)
   - **Note:** Polymorphic relationship via Notifiable_Type='Student' and Notifiable_ID

### ADDITIONAL RELATIONSHIPS (5):

9. **DEPARTMENTS → USERS**
   - **Label:** "has"
   - **Cardinality:** 1 Department to Many Users (1:N)
   - **FK:** Users.Department_ID → Departments.Department_ID

10. **SUBJECTS → MAKEUPCLASSREQUEST**
    - **Label:** "requested for"
    - **Cardinality:** 1 Subject to Many MakeUpClassRequest (1:N)
    - **FK:** MakeUpClassRequest.Subject_ID → Subjects.Subject_ID

11. **SECTIONS → STUDENTS**
    - **Label:** "has"
    - **Cardinality:** 1 Section to Many Students (1:N)
    - **FK:** Students.Section_ID → Sections.Section_ID

12. **DEPARTMENTS → FACULTYLOADINGHEADER**
    - **Label:** "manages"
    - **Cardinality:** 1 Department to Many FacultyLoadingHeader (1:N)
    - **FK:** FacultyLoadingHeader.Department_ID → Departments.Department_ID

13. **FACULTYLOADINGHEADER → FACULTYLOADINGDETAIL**
    - **Label:** "contains"
    - **Cardinality:** 1 FacultyLoadingHeader to Many FacultyLoadingDetail (1:N)
    - **FK:** FacultyLoadingDetail.Faculty_Loading_Header_ID → FacultyLoadingHeader.Faculty_Loading_Header_ID

---

## SUMMARY

**Total Entities:** 13
**Total Relationships:** 13

### Relationship Breakdown:
- **Request Flow:** Users → MakeUpClassRequest → Confirmation, Approval, Schedule
- **Student Flow:** Sections → Students → Confirmation, Notification
- **Approval Flow:** Users → Approval → MakeUpClassRequest
- **Schedule Flow:** MakeUpClassRequest → Schedule ← Room
- **Department Structure:** Departments → Users, FacultyLoadingHeader
- **Subject/Section Structure:** Subjects → MakeUpClassRequest, Sections → Students
- **Faculty Loading:** FacultyLoadingHeader → FacultyLoadingDetail

**Key Structure:**
- MakeUpClassRequest is the central hub connecting to 5 entities
- Departments is the parent entity for organizational structure
- All relationships follow one-to-many (1:N) or many-to-one (N:1) cardinality

---

## DRAWING TIPS

1. **Start with Departments** at the top center
2. **Draw vertical connections** within each column first
3. **Draw horizontal connections** last (Departments to children)
4. **Use curved lines** for any diagonal connections if needed
5. **Schedule at bottom center** - all connections go UP to it

**This layout minimizes crossings by keeping related entities in the same column!**

