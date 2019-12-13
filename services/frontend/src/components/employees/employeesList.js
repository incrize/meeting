import React from 'react';
import { queryGet } from '../../api';
import { useQuery } from '@apollo/react-hooks';
import { EmployeesItem } from './employeesItem';

export function EmployeesList() {
  const { loading, error, data } = useQuery(queryGet);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error :(</p>;

  let dat = data.employeesList.items.map(({
      id,
      name
    }) => (
      <EmployeesItem key={id} name={name}/>
  ))

  return (
    <ol>
      {dat}
    </ol>
  );
}
