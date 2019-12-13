import React from 'react';
import { queryGet } from '../../api';
import { useQuery } from '@apollo/react-hooks';

export function EmployeesList() {
  const { loading, error, data } = useQuery(queryGet);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error :(</p>;

  let dat = data.employeesList.items.map(({
      id,
      name
    }) => (
    <li key={id}>
      {name}
    </li>
  ))

  return (
    <ol>
      {dat}
    </ol>
  );
}
