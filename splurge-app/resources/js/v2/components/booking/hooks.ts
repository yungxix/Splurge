import { useContext} from 'react';
import { BookingContext, BookingEnvContext } from './context';


export const useTier = () => {
    const ctx  = useContext(BookingContext);
    const {tiers} = useContext(BookingEnvContext);
    return tiers.find(x => x.id === ctx.data.selected_tier);

};
