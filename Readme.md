<h3>Proste API służące do wyliczania kwoty za delegacje.</h3>

<br>
Najważniejsze funkcje:
<br>
a) kwota zależy od stawki kraju, w jakim jest delegacja<br>
b) długości delegacji (minimalna długość delegacji to 8h)<br>
c) do naliczania kwoty nie są wliczane weekendy<br>
d) jeden użytkownik może być tylko na jedej delegacji jednocześnie.



<h3>Dokumentacja</h3>
URL wejściowe:
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
dane wejściowe
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
<br>
<h3>Instalacja:</h3>
1. pobrać projekt <b> git clone https://github.com/kkonwinski/mindento-api.git </b><br>
2. przejść do katalogu projektu wykonać komendę <b>composer install</b><br>
3. <b>php bin/console make:migration</b><br>
4. <b>php bin/console doctrine:migrations:migrate</b>
5. mozna zamiast pkt. 3 i 4 <b>php bin/console doctrine:schema:update --force</b><br>
5. <b>symfony server:start</b> lub w katalogu <i>/public</i> <b>php -S localhost:8000<br>
