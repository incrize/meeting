import React from 'react';
import { ListEmployees } from './listEmployees';
import { AddEmployees } from './addEmployees';

export function Employees() {
  
  return (<div>
    <h2>Employees</h2>
    <AddEmployees/>
    <ListEmployees/>
  </div>);
}
