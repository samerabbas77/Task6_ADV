Here's a sample `README.md` file for your **Team Project Management System**:

---

# Team Project Management System

This project is a **Team Project Management System** built using Laravel 10. The system allows team members to manage projects and tasks with different roles and permissions, such as Manager, Developer, and Tester. The API allows for creating, updating, and managing projects, tasks, and user roles within the team.

## Features

- Manage Projects: Create, read, update, and delete projects.
- Manage Tasks: Create, update, and delete tasks associated with projects.
- Role-based Access Control:
  - **Manager**: Can create and edit tasks.
  - **Developer**: Can only update task status.
  - **Tester**: Can add comments to tasks.
- Task Filtering: Filter tasks by status or priority using `whereRelation`.
- Task Sorting: Retrieve the latest or oldest task using `latestOfMany` and `oldestOfMany`.
- Get Highest Priority Task: Fetch the task with the highest priority based on custom conditions using `ofMany`.

## Prerequisites

Before running this project, ensure you have the following installed:

- PHP >= 8.1
- Composer
- MySQL or any other database supported by Laravel
- Laravel 10
- Postman (for API testing)

## Project Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/your-repo/team-project-management-system.git
   ```

2. Install project dependencies:

   ```bash
   cd team-project-management-system
   composer install
   ```

3. Configure your `.env` file:

   ```bash
   cp .env.example .env
   ```

   Update the database configuration to match your setup:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```
5. Generate the application key:

   ```bash
   php artisan key:generate
   ```
   
6. Run the migrations to create the required tables:

   ```bash
   php artisan migrate
   ```


7. Start the development server:

   ```bash
   php artisan serve
   ```

## Database Structure

The following tables are created in the database:

- `projects`: Stores project details (name, description).
- `tasks`: Stores task details (title, description, status, priority, due_date).
- `users`: Stores user details (name, email, password).
- `project_user`: A pivot table that stores additional information such as:
  - `role`: Role of the user in the project (manager, developer, tester).
  - `contribution_hours`: Number of hours contributed by the user.
  - `last_activity`: Last activity timestamp for the user in the project.

## API Endpoints

The following API endpoints are available for managing the system. You can import the provided **Postman Collection** to test them:

### Projects

- **GET /api/projects** - Get all projects.
- **POST /api/projects** - Create a new project (Manager only).
- **GET /api/projects/{id}** - Get project details by ID.
- **PUT /api/projects/{id}** - Update a project (Manager only).
- **DELETE /api/projects/{id}** - Delete a project (Manager only).

### Tasks

- **GET /api/projects/{project_id}/tasks** - Get all tasks for a project.
- **POST /api/projects/{project_id}/tasks** - Create a new task (Manager only).
- **PUT /api/tasks/{task_id}** - Update a task (Manager/Developer).
- **DELETE /api/tasks/{task_id}** - Delete a task (Manager only).
- **PATCH /api/tasks/{task_id}/status** - Update task status (Developer only).
- **POST /api/tasks/{task_id}/comments** - Add a comment to a task (Tester only).

### Task Filtering & Sorting

- **GET /api/projects/{project_id}/tasks?status={status}&priority={priority}** - Filter tasks by status and priority.
- **GET /api/projects/{project_id}/tasks/latest** - Get the latest task in a project.
- **GET /api/projects/{project_id}/tasks/oldest** - Get the oldest task in a project.
- **GET /api/projects/{project_id}/tasks/highest-priority** - Get the highest priority task with a specific condition.

## Relationships

- **Project-User Relationship (Many-to-Many)**: Uses a pivot table `project_user` to store additional data like role, contribution hours, and last activity.
- **User-Task Relationship**: Users can access tasks related to their assigned projects using `hasManyThrough`.
- **Task Filtering**: Users can filter tasks by status or priority using `whereRelation`.

## Code Documentation

All functions are documented using **DocBlocks** for better clarity and maintainability. Please refer to the codebase for more details.

## Permissions System

- **Manager**: Can add, edit, and delete tasks.
- **Developer**: Can only update the status of tasks.
- **Tester**: Can add comments to tasks.

## Postman Collection

A **Postman Collection** is included in the repository for testing the API endpoints. You can import it into Postman and use it to test various API functionalities.

https://documenter.getpostman.com/view/34411360/2sAXqqcNfi

## Testing the Application

You can use Postman to test the API endpoints as defined in the **API Endpoints** section. Make sure the server is running and the database is properly set up.

## License

This project is open-source and licensed under the [MIT license](LICENSE).

---

This `README.md` outlines the system structure, how to set up the project, the available API endpoints, and how to test the system using Postman.
