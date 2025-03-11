import { Suspense } from 'react'
import Properties from './properties';
import PropertyFilter from './property-filter';
import { ErrorResponse, HomeProps, PaginatedResponse, Property } from '@/types';
import Pagination from './pagination';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

export default async function PropertyHome({ searchParams }: HomeProps) {
  const { page = '1', province = '', title = '', order = "", order_by = "" } = await searchParams;

  const data = await fetch(
    `http://localhost:80/api/properties?page=${page}&province=${province}&title=${title}&order_by=${order_by}&order=${order}`,
    {
      cache: "no-store",
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    }
  );
  const response: PaginatedResponse<Property> | ErrorResponse = await data.json();

  return (
    <div className="grid items-center justify-items-center p-8 sm:py-8 sm:px-20">
      <h1 className="text-3xl font-bold mb-6">Real Estate Listings</h1>
      <PropertyFilter />
      <Suspense fallback={<div>Loading...</div>}>
        {data.ok ? (
          <>
            <Properties properties={(response as PaginatedResponse<Property>).data} />
            {((response as PaginatedResponse<Property>).meta.to > 1) && (
              <Pagination page={(response as PaginatedResponse<Property>).meta} />
            )}
          </>
        ) : (
          // Display error message using ShadCN Alert
          <Alert variant="destructive" className='m-10'>
            <AlertTitle>Error</AlertTitle>
            <AlertDescription>
              {(response as ErrorResponse).message}
            </AlertDescription>
          </Alert>
        )}
      </Suspense>
    </div>
  )
}
