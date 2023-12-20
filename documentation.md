# COGIP API Documentation

## Introduction
This document describes the endpoints and usage of the COGIP API, designed to provide backend services for the COGIP web application.

## Base URL
The base URL for the API is: `https://cogip-api-8d6f281a9687.herokuapp.com/`

## Authentication
(Provide details on the authentication process, if applicable.)

## Endpoints
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



Error Codes and Messages
(Describe common error responses and their meanings.)

Rate Limits
(Information about any rate limits on API requests, if applicable.)

Contact and Support
For any queries or support related to the API, please contact [Tom Boszko](https://github.com/tomboszko).



