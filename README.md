
# Ewidencja czsu pracy

## Opis 

      Program służy do prowadzenia ewidencji czasu pracowników, dzięki rekordom jakie powstają przy 'odbijaniu' się pracowników na urządzeniu przy wejściu do pracy.
      Program posiada dwa główne widoki w zależności od przywilejów zalogowanego użytkownika tj. admin czy user.
      User posiada informacje o swoich logach (odbiciach) z podziałem na dni i miesiące. Nieprawidłowości w rejestrze logów (gdy pracownik zapomni się odbić) są odpowiednio oznaczane. User może wysłać prośbę o dodanie zapisu w danym dniu i czasie.
      Admin posiada informacje o obecnych pracownikach w czasie rzeczywistym (wymagane ciągła aktualizacja logów // szczegóły w ## Uruchamianie -> Zaciąganie danych z urządzenia -> przez komodę)
      Admin może dodawać, edytować, usuwać użytkowników. Ustalać pensję, akceptować/odrzucać prośby użytkowników lub dodawać własne rekordy.
      Admin ma kontrole nad tabelą raw_logs może czyścić i robić upload rekordów do bazy danych (z poziomu przeglądarki).


      Najważniejsze informacje o tabelach w bazie danych:

      - raw_logs - surowe dany z urządzania kolumny: employee_id(id pracownika), date_time(data i czas rekordu)
      - added_logs - prośby o dodanie/ dodane logi (w zależności od ustawionych flag is_approved, is_active itd..) 
      - deleted_logs - jeśli w tej tabeli i tabeli "raw-logs" date-time się powtórzą rekord nie jest brany pod uwagę (tabela naradzie nie jest wykorzystywana).
      - employees - tabela u pracownikami, ważne jest aby id danego rekordu odpowiadało id w urządzeniu, email musi być taki jak przy logowaniu, określa przywileje pracownika/usera.
      - salaries - pensje, przez kogo nadana dla kogo i od kiedy
      - users - name, email, password zahashowany. Tabela jest wykorzystywana gdy logowanie następuje przez bazę danych.
      - migrations - tabela frameworka zapisuje zmiany w strukturze bazy danych.
      - password_reset_tokens i personal_access_tokens - tabele frameworka, wykorzystywane przy logowaniu przez bazę danych.


## Wymagania/środowisko

### php >= 8.2
       Przez [apt install] domyślnie zainstalowało 8.1 wiec zainstalowałem z tej strony: https://php.watch/articles/install-php82-ubuntu-debian (na 8.1 później są problemy z instalowaniem zależności composera)
### composer
      https://getcomposer.org/doc/00-intro.md
            php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
            php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
            sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
### nodejs z npm (npm jest zazwyczaj instalowany z node)
      curl -sL https://deb.nodesource.com/setup_lts.x | sudo -E bash
      sudo apt-get install -y nodejs
      node -v
### apache2 + mariaDB
      sudo apt install apache2
      sudo apt install php libapache2-mod-php
      sudo apt install mariadb-server
      sudo apt install php-mysql   (dla obsługi MySQL/MariaDB)
      sudo mysql_secure_installation (konfiguracja)

- plik index.php znajduję się w ewidencja/public 

- plik konfiguracyjny apacha wyglądał mniej wiecej tak: 
      <VirtualHost *:80>
            ServerAdmin webmaster@localhost
            DocumentRoot /var/www/html/ewidencja/public

            <Directory /var/www/html/ewidecja>
                  Options Indexes FollowSymLinks
                  AllowOverride All
                   Require all granted
            </Directory>

            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
      </VirtualHost>

## Instalacja
      1. Sklonuj repozytorium gita
            git colne .....

      2. Przejdź do folderu projektu:
            cd Ewidencja

      3. Zainstaluj zależności PHP poprzez Composer:
            composer install
      
      4. Zainstaluj zależności JavaScript poprzez NPM:
            npm install

## Konfiguracja:  

      1. Skopiuj plik .env.example do .env:
            cp .env.example .env

      2. Wygeneruj klucz aplikacji Laravel:
            php artisan key:generate

            - ja musiałem jeszcze włączyć moduł rewrite:
                  sudo a2enmod rewrite
      
      3. Skonfiguruj dane bazy danych w pliku .env.
      4. Skonfiguruj sposobu logowania LOGIN_PROVIDER=users (lub =usersLdap) więcej w //Uruchamianie. 
      5  Skonfiguruj połącznie ldap (w pliką dane wpisane pochodzą z serwera testowego) 
                  https://www.forumsys.com/2022/05/10/online-ldap-test-server/
            - testowanie połączenia ldap:
                  php artisan ldap:test

      !!! Pamiętaj  aby nadać odpowiednie uprawnienia dla katalogu storage

## Tworzenie bazy danych / Kompilacja zasobów:
      1. Uruchom migracje bazy danych: (zostanie stworzona baza o nazwie zdefiniowanej w env. DB_DATABASE=)
            php artisan migrate
      
      2. Stwórz  plik users.txt w katalogu  storage/app oraz wypełnij go danymi pracowników w ten sposób:
            #|id|name|email|firstname|lastname|is_premia|is_active|is_admin
            1|kowalski|kowalski@example.pl|Jan|Kowalski|0|1|1

            - pola id, name, email są wymagane, resztę będzie można później modyfikować, oraz dodawać kolejnych użytkowników 
                  !!! należy stworzyć  przynajmniej jednego admina tj. is_admin == 1
                  !!! email musi być poprawny - będzie  służył do identyfikacji przy logowaniu 
                  !!! id musi być takie jakie jest przypisane do pracownika w urządzeniu 'odbijającym'
            
      3. WWypchnij dane userow do bazy danych: 
            php artisan app:upload-users

 - Kompilacja:
      w trybie produkcyjnym: 
            npm run build

      w trybie deweloperskim:
            npm run dev
      
      !!! W trybie produkcyjnym należy w pliku .env APP_DEBUG ustawić na false

## Uruchamianie

      Gdyby były jakieś problemy związane z uruchomieniem aplikacji a apache, można spróbować użyć servera laravela:
            php artisan serve
      
      Po uruchomieniu aplikacji i przejściu na skonfigurowany adres np: http://localhost zostaniemy automatycznie przekierowaniu na stronę logowanie: localhost/login

      Aby użytkownik mógł korzystać z systemu jego dane muszą być w bazie danych(## Tworzenie bazy danych / Kompilacja zasobów 2 i 3 pkt.) tabeli employess, podczas logowania następuje skojarzenie podanego emaila z danymi w ww. tabeli. Samo logowanie może przebiegać dwoma sposobami (nie jednocześnie) przez połączenie z serwerem ldap lub klasycznie przez rejestracje użytkownika i zapisanie jego danych w bazie danych.


### logowanie przez ldap
      !!! w pliku .env należy ustawić LOGIN_PROVIDER=usersLdap

      Jeśli uda się poprawnie skonfigurować połączenie z serwerem ldap należy podać email oraz password, które wykorzystuje się do logowania np. na pocztę.
      Jeśli serwer ldap zwróci obiekt użytkownika, jednak nie będzie on istniał w tabeli employees (email nie będzie się zgadzał). System zaloguje go, jednak wyświetli mu jedynie informacje o kontakcie z adminem.
      Jeśli użytkownik nie istnieje dla servera ldap, logowanie nie powiedzie się.


### logowanie przez baze danych (rejstracje użytkowników) 
      !!! w pliku .env należy ustawić LOGIN_PROVIDER=users

      Użytkownik aby mógł korzystać z systemu musi najpierw się zarejestrować (strona logowania -> button REGISTER)
      ! Ważne jest aby podczas rejestracji podał dokładnie taki sam e-mail jaki jest w tabeli employees
      Gdy Użytkownik zapomni hasła, admin musi go usunąć z tabeli users, po czym użytkownik powtarza rejestracje (requesy są przetrzymywane w tabeli employees dlatego żadne dane nie zostaną utracone)

      Podane dane w pliku .env pozwalają na połącznie z serverem testowym ldap: https://www.forumsys.com/2022/05/10/online-ldap-test-server/
            po połączeniu można spróbować logowania na dane: 
            email:
                  tesla@ldap.forumsys.com lub newton@ldap.forumsys.com

            hasło:
                        password 


### Zaciąganie danych z urządzenia 
      Plik list.txt (def.name LOG_FILE_NAME) należy umieścić w storage/app
      Istnieją dwa sposoby na zrobienie upload danych
            - przez system:
              Admin loguje się do aplikacji Log List -> button UPlOAD LOG

            - prze komodę:
              php artisan app:upload-logs
             * skrypt można zapętlić dane przed upload są walidowane więc nic szczególnego nie powinno się stać. 


      Plik nie musi być szczególnie przygotowany, rekordy mogą być z dowolnego okresu i się powtarzać.
      Testowałem na 30k rekordów i nie było problemów, upload przebiega sprawnie.
