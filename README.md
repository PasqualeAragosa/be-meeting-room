# PROJECT BACK-END ON MEETING ROOM WEB PAGE

-   Crea un back-end comprensivo di tutte le CRUD

    -   GET reservations
        -   paginations and query param on search-box
    -   GET show single reservation
    -   POST create and store reservation
    -   PUT change and update reservation
    -   DELETE reservation

-   Validazione dei campi richiesta

    -   Controllo su orario ecc ecc

-   Aggiungere 'Note' all'output

-   Momentaneamente senza autenticazione

-   AGGIUNGERE ALLA REPO I TEACHERS

_----------------------------------_

## Ricevere la lista delle prenotazioni

-   path: /reservations
-   method: GET
-   response:

```js

[
  {
    "id": "res_1",
    "user": {
      "name": "Mario",
      "surname": "Rossi"
    },
    "date": "28/02/2023",
    "timeFrom": "12:00",
    "timeTo": "13:00"
  },
  ...
]

```

## Ricevere la singola prenotazione

-   path: /reservations/:id
-   method: GET
-   response:

```js

{
    "id": "res_1",
    "user":
        {
            "name": "Mario",
            "surname": "Rossi"
        },
    "date": "28/02/2023",
    "timeFrom": "12:00",
    "timeTo": "13:00"
}

```

## Creare una nuova prenotazione

-   path: /reservations
-   method: POST
-   body:

```js

{
    "user":
        {
            "name": "Mario",
            "surname": "Rossi"
        },
    "date": "28/02/2023",
    "timeFrom": "12:00",
    "timeTo": "13:00"
}

```

## Modificare una nuova prenotazione

-   path: /reservations/:id
-   method: PUT
-   body:

```js

{
    "user": {
      "name": "Mario",
      "surname": "Rossi"
    },
    "date": "28/02/2023",
    "timeFrom": "12:00",
    "timeTo": "13:00"
}

```
