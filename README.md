1. Clone project
2. Setup database credentials in .env
3. Run `composer install`
4. Run `php bin/console doctrine:database:create` 
5. Run `php bin/console doctrine:migrations:migrate` 

List of endpoints:
  classroom_list           GET                /classroom
  
  classroom_get_active     GET                /classroom/active
  
  classroom_get            GET                /classroom/{id}
  
  classroom_create         POST               /classroom
  
  classroom_update         PUT                /classroom/{id}
  
  classroom_delete         DELETE             /classroom/{id}
  
  classroom_toggle_state   PATCH              /classroom/{id}
