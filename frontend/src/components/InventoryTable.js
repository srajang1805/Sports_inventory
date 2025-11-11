import React from 'react';
import '../App.css';

export default function InventoryTable({ items }) {
  return (
    <div className="table-wrapper">
      <h2>🏋️ Equipment Inventory</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Equipment</th>
            <th>Brand</th>
            <th>Quantity</th>
            <th>Condition</th>
          </tr>
        </thead>
        <tbody>
          {items.length > 0 ? (
            items.map((item) => (
              <tr key={item.item_id}>
                <td>{item.item_id}</td>
                <td>{item.item_name}</td>
                <td>{item.brand}</td>
                <td>{item.quantity}</td>
                <td>{item.item_condition}</td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="5" className="center">
                No items available 🏸
              </td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
}
