import {Tab} from '@mui/material'
interface StyledTabProps{
    label:string,
    value:string
}
const StyledTab = ({label,value}:StyledTabProps) => {
  return (
    <Tab sx={{textTransform:"capitalize"}} label={label} value={value}/>
  )
}

export default StyledTab