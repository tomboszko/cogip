# COGIP API Documentation

## Introduction
This document describes the endpoints and usage of the COGIP API, designed to provide backend services for the COGIP web application.

## Base URL
The base URL for the API is: `https://cogip-api-8d6f281a9687.herokuapp.com/`

## Authentication
(Provide details on the authentication process, if applicable.)

## Endpoints

### Invoices

#### Get All Invoices
- **Endpoint:** `/invoices`
- **Method:** GET
- **Description:** Fetches all invoices with optional pagination.
- **Parameters:**
  - `page` (optional): Page number for pagination.
- **Example Request:** `GET /invoices?page=1`
- **Example Response:**
  ```json
  {
    "invoices": [...],
    "totalPages": 5
  }
Get Invoice by ID
Endpoint: /invoices/{id}
Method: GET
Description: Fetches a single invoice by its ID.
Parameters:
id: Invoice ID.
Example Request: GET /invoices/123
Example Response:
json
Copy code
{
  "id": 123,
  "details": {...}
}
(Continue in a similar format for POST, PUT, DELETE methods for invoices, and other entities like companies and contacts.)

Companies
(Endpoints and details for company-related operations.)

Contacts
(Endpoints and details for contact-related operations.)

Error Codes and Messages
(Describe common error responses and their meanings.)

Rate Limits
(Information about any rate limits on API requests, if applicable.)

Contact and Support
For any queries or support related to the API, please contact [Your Contact Information].

Versioning
Current API version: v1.0.0
(Include details about your versioning strategy and how updates are managed.)

