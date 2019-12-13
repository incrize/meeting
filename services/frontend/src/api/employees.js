import { gql } from "apollo-boost";

export const queryGet = gql `
  {
    employeesList {
      items {
        id,
        name
      }
    }
  }
`;

export const queryAdd = gql `
mutation EmployeeCreate($data: EmployeeCreateInput!) {
  employeeCreate(data: $data) {
    id
    name
    status
  }
}
`;