export interface ITable
{
  users?:Users[]
  incidents?:Incidentsobj[]
  products?:Product[]
  info:string
}
export interface Users
{
    id:number
    FirstName:string,
    LastName:string,
    Email:string,
    Position:string,
    Location:string
}
export interface Incidentsobj
{
    id:number
    Student:string,
    SerialNumber:string,
    Problem:string,
    Status:string,
    Created:string,
}
export interface Product
{
    id:number
    label:string,
    productNumber:string,
    serialNumber:string,
    model?:string,
    user?:{firstName:string,lastName:string},
    function?:string,
    location?:string
}