import styles from "./DoughnutChart.module.css"
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut } from 'react-chartjs-2';
import WidgetBox from "../WidgetBox/WidgetBox";
import { MouseEventHandler } from "react";

ChartJS.register(ArcElement, Tooltip, Legend);

export const data = {
    labels: ['Users', 'Incidents', 'Devices'],
    datasets: [
      {
        data: [12, 19, 3],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)',
        ],
        borderWidth: 1,
      },
    ],
  };

  interface DoughnutChartProps{
    editState:boolean,
    name:string,
    handleRemove : MouseEventHandler<HTMLSpanElement>
  }
const DoughnutChart = ({editState,name,handleRemove}:DoughnutChartProps) => {
  return (
    <WidgetBox editState={editState} name={name} handleRemove={handleRemove} >
        <Doughnut className={styles.doughnut} data={data}   options={{
            plugins:{
                legend:{
                    onHover: () => {
                    document.body.style.cursor="pointer";
                    },
                    onLeave:() => {
                      document.body.style.cursor="default";
                    }
                }
            }
        }}  />
    </WidgetBox>
  )
}

export default DoughnutChart