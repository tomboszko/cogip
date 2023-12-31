# COGIP Project - Backend Focus

## Deployed project: [COGIP](https://cogip-seven.vercel.app/)

## Documentation : [How to cogip](https://github.com/tomboszko/cogip/blob/bcd722093c11f7743d8662572bc42ac159ccce2d/documentation.md)

## About The Project

Developed as part of the BeCode bootcamp, the COGIP project is a web application designed to modernize the accounting process for Jean-Christian Ranu at COGIP. This application primarily focuses on backend functionalities to enhance efficiency and user experience in accounting tasks.

## Backend Technologies

**PHP**: Used for server-side logic.

**POO** (Object-Oriented Programming): Enhances code modularity and reusability.

**MVC** (Model-View-Controller): Organizes code structure for efficient development and maintenance.

**Namespace**: Manages and encapsulates classes.

**Bramus Router**: A lightweight PHP router for handling HTTP requests.

**Filp/Whoops**: For elegant error handling in PHP.

## Team  Contributions

### backend

[Bastien](https://github.com/bastien-venturi): Focused on setting up the MVC architecture and integrating the Bramus Router for efficient request handling.

Thomas (me): Worked on database interactions, including CRUD operations, and implemented filp/whoops for error management.

#### Backend Functionality

**CRUD Operations**: Create, Read, Update, and Delete functionality for managing invoices, companies, and contacts.

**Validation** & Sanitization: Ensures data integrity and security.

**API Development**: 

We provided to the frontend's the URL to deployed project on Heroku :

[https://cogip-api-8d6f281a9687.herokuapp.com/](https://cogip-api-8d6f281a9687.herokuapp.com/)

and, for sure, all the endpoints as following:

| Endpoint | Method | Description |
| --- | --- | --- |
| /api/routes | GET | Fetches and map all routes. |
| /invoices | GET | Fetches all invoices with pagination. |
| /invoices/{id} | GET | Fetches a single invoice by its ID. |
| /invoices | POST | Creates a new invoice. |
| /invoices/{id} | PUT | Updates an existing invoice by its ID. |
| /invoices/{id} | DELETE | Deletes an invoice by its ID. |
| /companies | GET | Fetches all companies with pagination. |
| /companies/{id} | GET | Fetches a single company by its ID. |
| /companies | POST | Creates a new company. |
| /companies/{id} | PUT | Updates an existing company by its ID. |
| /companies/{id} | DELETE | Deletes a company by its ID. |
| /contacts | GET | Fetches all contacts with pagination. |
| /contacts/{id} | GET | Fetches a single contact by its ID. |
| /contacts | POST | Creates a new contact. |
| /contacts/{id} | PUT | Updates an existing contact by its ID. |
| /contacts/{id} | DELETE | Deletes a contact by its ID. |
| /companies/{id}/show | GET | Fetches all contacts for a specific company by company ID. |
| /types | GET | Fetches all contacts without pagination |
| /fetchcompanies | GET | Fetches all companies with ID and no pagination |
| /invoices/last | GET | Fetches the last 5 invoices created |
| /companies/last | GET | Fetches the last 5 companies created |
| /contacts/last | GET | Fetches the last 5 contacts created |

**Database Structure**

![dbStructure](https://github.com/tomboszko/cogip/assets/132305348/2bf8d747-43cd-41bc-b243-63919fc7540a)

#### Future Enhancements

**Improving API security:** 

due to a lack of time, we didn't implemented authentication and ApiKey. We will see later to implement these features.

**Optimizing database queries for performance:**

We used db4free.net to host our database and for this litlle project it works but requests take a lot of time... (but it's free...)  

Implementing additional backend features as per project requirements.

### frontend

The API created by Bastien & myself, is called by our frontend teammates :[COGIP](https://cogip-seven.vercel.app/)

[Antoine](https://github.com/antoinel74) and [Pierre](https://github.com/Pierremarien)

You can find the repo here: [Linkt to the repo frontend side](https://github.com/antoinel74/COGIP)



