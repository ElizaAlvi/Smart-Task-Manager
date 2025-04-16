#  Student Task Management System

A web-based task management system for students and teachers, built with PHP, MySQL, and Bootstrap 5. The platform enables teachers to assign tasks to students based on their branch and academic year, and allows students to manage both academic and personal tasks from a dedicated dashboard.

---

## Features

### Teacher Module
- Secure login system
- Assign tasks to students by **branch** and **year**
- View all previously assigned tasks in a clean table
- Navigate between dashboard and task assignment
- Responsive and modern UI

### Student Module
- Secure login using **PRN number**
- Personalized dashboard with:
  - Student's **name**, **branch**, and **year**
  - Assigned academic tasks based on eligibility
  - Personal task manager with:
    - Status update dropdown (`Not Started`, `Started`, `Completed`)
    - Auto-hide completed tasks
    - Option to delete tasks
- Clean, responsive design using Bootstrap 5
- Sticky footer with copyright

---

##  Tech Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Backend**: PHP
- **Database**: MySQL
- **Local Server**: XAMPP

---

## How to Run Locally

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/student-task-manager.git
   cd student-task-manager
