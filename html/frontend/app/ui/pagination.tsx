"use client"

import { ChevronLeft, ChevronRight, } from "lucide-react"
import { Button } from "@/components/ui/button"
import { PaginationMeta } from "@/types"
import { useSearchParams, useRouter } from "next/navigation";

export default function Pagination({ page }: { page: PaginationMeta }) {

    const router = useRouter();
    const searchParams = useSearchParams();

    const handlePage = (pageNumber: number) => {
        const params = new URLSearchParams(searchParams.toString());
        params.set("page", pageNumber.toString());
        router.push(`?${params.toString()}`);
    }

    return (
        <div className="flex justify-center items-center gap-4 mt-8">
            <Button
                variant="outline"
                onClick={() => handlePage(page.current_page - 1)}
                disabled={page.current_page === 1}
                className="flex items-center gap-1"
            >
                <ChevronLeft className="h-4 w-4" />
                Previous
            </Button>

            <span className="text-sm">
                Page {page.current_page} of {page.last_page}
            </span>

            <Button
                variant="outline"
                onClick={() => handlePage(page.current_page + 1)}
                disabled={page.current_page === page.last_page}
                className="flex items-center gap-1"
            >
                Next
                <ChevronRight className="h-4 w-4" />
            </Button>
        </div>
    );
}
