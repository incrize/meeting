import React from 'react';
import { queryGet } from '../../api';
import { useQuery } from '@apollo/react-hooks';
import { ItemEmployees } from './itemEmployees';

let st = [];

export function ListEmployees() {
  const { loading, error, data } = useQuery(queryGet);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error :(</p>;

  st.push(...data.employeesList.items);

  let dat = st.map(({
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
