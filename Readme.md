URL wejsciowe:
1. tworzenie nowego pracownika:
<br><b>localhost:8000/api/addDelegation/addEmployee</b>
<br>
Przy uruchomieniu wysyłki POST trzeba ustawić puste body z klamrami <b>{}</b> !!!
2. tworzenie nowej delegacji:
<br>
<b>localhost:8000/api/addDelegation
</b>
<br><br>
dane wejsciowe 
<br>
<br>
{
    "start": "2020-04-20 16:00:00",
    "end": "2020-04-21 16:10:00",
    "country": "PL|GB|DE",
    "employeeId": employeeId
}
3. pokaż wszystkie delegacje dla pracownika
<br>
<b>localhost:8000/api/showEmployeeDelegations/{employeeId}</b>




