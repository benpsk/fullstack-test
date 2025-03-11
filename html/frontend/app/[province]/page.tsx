import { HomeProps } from '@/types';
import PropertyHome from '../ui/property-page';

export default async function Home({ searchParams }: HomeProps) {
  return (
    <PropertyHome searchParams={searchParams} />
  )
}
