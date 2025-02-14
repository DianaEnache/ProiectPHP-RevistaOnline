# 📖 Revista Online

## Overview
**Revista Online** is a web-based magazine platform designed to offer users a seamless reading and publishing experience. It provides a secure and user-friendly environment for both readers and administrators to engage with content efficiently.

---

## Deployment
The application can be hosted locally using **Apache + MySQL (XAMPP/LAMP)** or deployed on a remote hosting provider. 

🔗 **Live**: http://revistaonline.infinityfreeapp.com

---

## ✨ Features
1.**User Registration & Authentication**  
2.**Article Management (Create, Edit, Delete)**   
3.**Commenting System**   
4.**Search & Filtering for Articles**   
5.**Analytics for User Engagement**   
6.**Security Measures: reCAPTCHA, CSRF Protection, SQL Injection Prevention ...**   

---

## Architecture

### 🔹 User Roles:
- **User**: Can browse and read articles, as well as leave comments.
- **Editor**: Can create, edit, and manage articles.
- **Admin**: Oversees the entire platform, managing users, articles, and comments.

### 🔹 Core Entities:
- **Users**: Stores user information, roles, and authentication details.
- **Articles**: Contains published articles, metadata, and author details.
- **Comments**: Stores user comments linked to respective articles.
- **Analytics**: Tracks user interactions, page visits, and engagement metrics.

---

## Database Schema
The database consists of the following primary tables:
- **Users** (id, username, password, email,  role)
- **Articles** (id, title, content, author_id, imageURL created_date, category)
- **Comments** (id, user_id, article_id, comment, created_date, summary)
- **Analytics** (id, page, ip_address, visit_date)

---

## 🔧 Implementation Details

### Technology Stack
- **Frontend**: Bootstrap, HTML, CSS, JavaScript
- **Backend**: PHP 
- **Database**: MySQL 
- **Security Features**: reCAPTCHA, CSRF Protection, Prepared Statements, SQL Injection Prevention, and Cross-Site Scripting (XSS) protection.





## 📊 UML Diagrams
To better illustrate the system's workflows, the following UML diagrams are available:
- **Use Case Diagram** – Identifies interactions between user roles and system functionalities.
- **Sequence Diagram** – Demonstrates how processes like article publishing and commenting operate.
- **Component Diagram** – Shows the main components of the system and their interactions.
