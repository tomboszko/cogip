# COGIP API Documentation

## Introduction

This document describes the endpoints and usage of the COGIP API, designed to provide backend services for the COGIP web application.

## Base URL

The base URL for the API is: `https://cogip-api-8d6f281a9687.herokuapp.com/`

## Authentication

there's no authetication... lack of time... Sorry.

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

## Error Codes and Messages

**400 Bad Request**

Description: The request cannot be processed due to bad request syntax, invalid data, or a missing required parameter.

Example Message: "Invalid request. Please check the data and parameters you're sending."

**401 Unauthorized**

Description: The request lacks valid credentials for authentication, which is required for accessing certain endpoints.

Example Message: "Unauthorized access. Please provide valid authentication credentials."

**403 Forbidden**

Description: The request is understood, but it has been refused or access is not allowed.

Example Message: "Access denied. You do not have permission to access this resource."

**404 Not Found**

Description: The server can't find the requested resource. This occurs when trying to access a non-existent invoice, company, or contact.

Example Message: "Resource not found. The requested invoice/company/contact does not exist."

**405 Method Not Allowed**

Description: The HTTP method used is not allowed for the requested resource.

Example Message: "Invalid method. Please check the HTTP method being used for your request."

**500 Internal Server Error**

Description: The server encountered an unexpected condition, often caused by server-side issues.

Example Message: "Internal server error. Please try again later or contact support if the problem persists."

**503 Service Unavailable**

Description: The server is currently unable to handle the request due to temporary overloading or maintenance.

Example Message: "Service temporarily unavailable. The server is either overloaded or under maintenance. Please try again later."

## Rate Limits

There are no rate limit.

## Contact and Support

For any queries or support related to the API, please contact [Tom Boszko](https://github.com/tomboszko).



