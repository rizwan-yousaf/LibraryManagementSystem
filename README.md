# LibraryManagementSystem

# Step -> 1
git clone https://github.com/rizwan-yousaf/LibraryManagementSystem.git

#  Step -> 2
cd LibraryManagementSystem

#  Step -> 3
git checkout master

#  Step -> 4
cp .env.example .env

#  Step -> 5
composer install

#  Step -> 6
php artisan key:generate

#  Step -> 7
php artisan migrate

#  Step -> 8: Install Passport for API authentication
php artisan passport:install

#  Step -> 9: Start server
php artisan serve

# IMPORTANT: Following the proper sequence, please add an author before creating a book.
# Example:
# - Use the appropriate functionality to add an author to the system
# - Then proceed to create books associated with the authors
