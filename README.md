**ARRANCAR LA APLICACIÓN:** 

1º lanzar commando:  docker-compose build
<br>
2º Arrancar la app con: docker-compose up -d
<br>
3º Ejecutamos composer install dentro de docker con :  docker-compose exec php composer install
<br>
4ª Creamos tablas en bbdd: docker-compose exec php php bin/console doctrine:schema:create 

**CONECTARNOS ADMINER:**

accedemos a http://localhost:8080/

rellenamos con los siguientes datos:
<br>
servidor:  mysql 
<br>
usuario: root
<br>
password: root
<br>
base de datos: auction_portal_db

**CREAR USUARIO ADMIN**

ejectuamos la siguiente consulta : 
<br>
INSERT app_users
SET 
username="admin",
password="$2y$13$JCDnaGVhLinp/rnwdXtL4umg.9HEUeGWb7GrwrbFsSbfYlEAch2ZW",
email="admin@gmai.com",
roles= 'a:1:{i:0;s:10:"ROLE_ADMIN";}'

<br>
Ya podemos hacer loggin con usuario "admin" y pass "admin"

