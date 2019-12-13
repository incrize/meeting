import React from 'react';
import { queryGet } from '../../api';
import { useQuery } from '@apollo/react-hooks';
import { ItemEmployees } from './itemEmployees';

export function ListEmployees() {
  const { loading, error, data } = useQuery(queryGet);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error :(</p>;

  let dat = data.employeesList.items.map(({
      id,
      name
    }) => (
      <ItemEmployees key={id} name={name}/>
  ))

  return (
    <ol>
      {dat}
    </ol>
  );
}
