URL wejsciowe:
1. tworzenie nowego pracownika:
<i>POST</i> <b>localhost:8000/api/addDelegation/addEmployee</b>
<br>
Przy uruchomieniu wysyłki POST trzeba ustawić puste body z klamrami <b>{}</b> !!!
2. tworzenie nowej delegacji:
<br><br>
<i>POST</i>
<b>localhost:8000/api/addDelegation
</b>
<br><br>
dane wejsciowe 
{
    "start": "2020-04-20 16:00:00",
    "end": "2020-04-21 16:10:00",
    "country": "PL|GB|DE",
    "employeeId": employeeId
}
<br><br>
3. pokaż wszystkie delegacje dla pracownika
<br><i>GET</i>
<b>localhost:8000/api/showEmployeeDelegations/{employeeId}</b>

Instalacja:
1. pobrać projekt <b> git clone https://github.com/kkonwinski/mindento-api.git </b>
2. przejść do katalogu projektu wykonać komendę <b>composer install</b>
3. php bin/console make:migration
4. php bin/console doctrine:migrations:migrate
5. <b>symfony server:start</b> lub w katalogu <i>/public</i>  <b>php -S localhost:8000




