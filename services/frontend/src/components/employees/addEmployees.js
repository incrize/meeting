import React from 'react';
import { queryAdd } from '../../api';
import { useMutation } from '@apollo/react-hooks';

export function AddEmployees() {
  let inputName;
  let inputStatus;

  const [addEmployee, { data }] = useMutation(queryAdd);

  console.log(data);
  
  return (
    <div>
      <form
        onSubmit={e => {
          e.preventDefault();
          addEmployee({
            variables: {
              data: {
                name: inputName.value,
                status: inputStatus.value
              }
            }
          });
        }}
      >
        <input
          ref={node => {
            inputName = node;
          }}
        />
        <input
          ref={node => {
            inputStatus = node;
          }}
        />
        <button type="submit">Add employee</button>
      </form>
    </div>
  );
}
