import React from 'react';
import './Navbar.css';

export default function Navbar() {
  function logout() {
    localStorage.removeItem('token');
    window.location = '/login';
  }

  return (
    <nav className="navbar">
      <div className="nav-title">🏏 Sports Inventory System</div>
      <div className="nav-links">
        <a href="/">🏠 Home</a>
        <a href="#">📦 Suppliers</a>
        <a href="#">🛒 Purchases</a>
        <a href="#" onClick={logout}>🚪 Logout</a>
      </div>
    </nav>
  );
}
