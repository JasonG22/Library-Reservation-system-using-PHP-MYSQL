PHP Book Reservation System

This project is a web-based book reservation system developed using PHP and MySQL. It allows users to register an account, log in, search for books, make reservations, and manage their reserved books. The system was designed with a focus on user authentication, database interaction, and clean session handling.

The application begins with user authentication. New users can register by providing their details through a registration form, which includes several validation checks such as minimum password length and unique username verification. Once registered, users can log in through the login page, where their credentials are checked against the database. If the login is successful, a session is started, and the user is redirected to their dashboard. From there, users can access the main features of the system.

The core functionality revolves around searching for books and reserving them. The search page allows users to filter by title, author, or category, with categories populated dynamically from the database. The search uses SQL queries with partial matching, so a keyword like “computer” will return results such as “Computers in Business.” If a book is already reserved, the reserve button is disabled to prevent duplicate reservations. When a user reserves a book, the reservation is stored in the database along with their username and a timestamp, and the book’s status is updated to reflect that it is no longer available.

Users can view their reservations on a dedicated page, which displays all the books they have reserved. From this page, reservations can also be removed, which updates the book’s availability in the database. This ensures that the reservation system reflects real-time availability. The system consistently uses a shared header and footer for navigation, includes clear success and error messages, and retains user input in forms to improve usability.

The project was built with PHP for server-side logic, MySQL for the database, and HTML and CSS for structure and styling. It also emphasizes secure coding practices such as using htmlspecialchars to protect against cross-site scripting.

Through building this system, I learned how to implement form validation in PHP, manage sessions to restrict access to certain pages, and perform CRUD operations with MySQL. I also gained experience with SQL joins to connect related data, such as linking books to their categories. Beyond the technical implementation, I developed a better understanding of how to enhance the user experience through features like dynamic error handling and input retention.

Future improvements to the project could include implementing password hashing for better security, improving the design with a responsive CSS framework such as Bootstrap or Tailwind and adding an administrator panel for managing books and users, 


