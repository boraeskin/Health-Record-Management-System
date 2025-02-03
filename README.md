# Healthcare Management System
![Image](https://github.com/user-attachments/assets/43cda619-6dd6-4a3e-8eca-eaebdf018ef7)
## Description

The Healthcare Management System is a web-based application designed to simplify and streamline appointment scheduling, prescription management, and administrative oversight for a healthcare facility. This project allows different types of Admins , Doctors, and Patients (Users) — to interact with the system based on their roles. 

Admins can manage appointments, view all prescriptions, and oversee the overall workflow. Doctors can view and manage their appointments and issue prescriptions. Patients can schedule appointments, view their appointments, and access their prescriptions. This system ensures a smooth and efficient healthcare experience for all users.

## Features
- **Admin Dashboard**: 
  - Manage appointments (create, edit, delete).
  - View all prescriptions.
  - Assign doctors to appointments.
  - Generate reports for administrative oversight.
  
- **Doctor Dashboard**:
  - View assigned appointments.
  - Issue prescriptions for patients.
  - View and manage appointment history.

- **Patient Dashboard**:
  - Schedule new appointments.
  - View upcoming and past appointments.
  - Access and review prescriptions issued by doctors.

   **Secure Authentication**:
  - Role-based login system for Admins, Doctors, and Patients.
  - Passwords are securely hashed and stored.

- **Responsive Design**:
  - Clean, user-friendly interface designed to work on all devices.

## How to Use the Project

### Prerequisites
  Before running the project, ensure you have the following installed:
  - PHP (8.0 or later)
  - MySQL (or any compatible database server)
  - A web server like Apache (XAMPP, WAMP, or LAMP recommended)
  - A web browser (for accessing the application)


  ### Setting Up the Project

1. **Clone or Download the Project**:
   - Clone this repository or download it as a ZIP file.

   ```bash
   https://github.com/boraeskin/Health-Record-Management-System

2. **Import the Database**:
- Open your database management tool (e.g., phpMyAdmin).
- Create a new database (e.g., project).
- Import the SQL file included in the project (project.sql) into the newly created database.

3. **Configure Database Connection:**:
- Open the db_connection.php file in the project directory.
- Update the following variables with your database credentials:

$host = "localhost";
$user = "root";
$password = "";
$dbname = "project";

4. **Run the Project:**:
- Place the project folder in your web server’s root directory (e.g., htdocs for XAMPP).
- Start the web server and database server (e.g., via XAMPP control panel).
- Access the application in your browser : 
      http://localhost/project
                     

## Project Structure

### Root Directory
- **assets/**
  - **css/**
    - `style.css`: Stylesheet for UI styling
  - **images/**: Placeholder for any images used in the project
- **config/**
  - `db_connection.php`: Database connection configuration
- **sql/**
  - `project.sql`: SQL file to set up the database
- **pages/**
  - **admin/**
    - `admin_dashboard.php`: Admin dashboard for managing appointments
    - `admin_prescriptions.php`: Admin view for all prescriptions
  - **doctor/**
    - `doctor_dashboard.php`: Doctor dashboard to view appointments
    - `doctor_prescription.php`: Doctor logic for issuing prescriptions
  - **user/**
    - `dashboard.php`: User dashboard for patients
    - `user_prescriptions.php`: User view for accessing prescriptions
  - **appointments/**
    - `create_appointment.php`: Logic to create new appointments
    - `edit_appointment.php`: Logic to edit an appointment
    - `delete_appointment.php`: Logic to delete an appointment
  - **auth/**
    - `login.php`: Login page for all users
    - `logout.php`: Logout logic to end user sessions
    - `signup.php`: Signup page for patients and doctors
    - `forgot_password.php`: Forgot password page for resetting credentials
- `index.php`: Main entry point (optional, for landing or routing)
- `README.md`: Project description and instructions



## Technologies Used
- Frontend: HTML, CSS, JavaScript
- Backend : PHP
- Database: MySQL
- Tools: Apache Web Server, phpMyAdmin

## Authors
- Bora Eskin
- Salih Aydos
