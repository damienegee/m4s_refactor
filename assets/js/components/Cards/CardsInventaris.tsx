import styles from './cards.module.css';
import { Product } from '../../../types';

const CardsInventaris  = ({data}:{data:Product}) => {
    return (<>

        <div className={styles.card}>
            <div className={styles.left}>
                    <div className={styles.row}>
                        Label : 
                    </div>
                    <div className={styles.row}>
                         Productnummer : 
                    </div>
                    <div className={styles.row}>
                         User : 
                    </div>
                    <div className={styles.row}>
                         Function : 
                    </div>
                    <div className={styles.row}>
                         Location : 
                    </div>
                    </div>
            <div className={styles.right}>
                    <div className={styles.row}>
                        {data.label} 
                    </div>
                    <div className={styles.row}>
                        {data.productNumber} 
                    </div>
                    <div className={styles.row}>
                        {data.user?.firstName} {data.user?.lastName}
                    </div>
                    <div className={styles.row}>
                        {data.function}
                    </div>
                    <div className={styles.row}>
                        {data.location}
                    </div>
            </div>
        </div>
    </>)
}
export default CardsInventaris;